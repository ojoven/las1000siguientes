<?php
/** BUILDER FUNCTIONS **/
class Functions {

	public static function log($variable) {

		if (is_string($variable)) {
			echo $variable;
		} else {
			print_r($variable);
		}

		echo PHP_EOL;
	}

	/** DB **/
	public static function selectDB($db, $query) {
		$result = $db->query($query);
		return $result;
	}

	public static function insertDB($db, $tableName, $data) {
		$dataParsed = self::implodeindexesvalues($data);
		$query = "INSERT INTO " . $tableName . " (" . $dataParsed['indexes'] . ") VALUES (" . $dataParsed['values'] . ")";
		$db->query($query);
	}

	public static function insertMultipleDB($db, $tableName, $indexes, $data) {
		$query = "INSERT INTO " . $tableName . " (" . implode(",", $indexes) . ") VALUES ";
		foreach ($data as $value) {
			$query .= "(" . self::addQuotesAndImplode($value) . "),";
		}
		$query = rtrim($query, ",");
		$db->query($query);
	}

	public static function addQuotesAndImplode($array) {
		foreach ($array as &$element) {
			$element = "'" . iconv("UTF-8", "CP1252", self::escapeSingleQuotes($element)) . "'";
		}
		return implode(",", $array);
	}

	public static function implodeindexesvalues($data) {
		$indexes = "";
		$values = "";
		foreach ($data as $index=>$value) {
			$indexes .= $index . ",";
			if (is_string($value)) {
				$value = self::escapeSingleQuotes($value);
			}
			$values .= "'". iconv("UTF-8", "CP1252", $value) . "',";
		}
		$dataParsed['indexes'] = rtrim($indexes, ',');
		$dataParsed['values'] = rtrim($values, ',');
		return $dataParsed;
	}

	public static function escapeSingleQuotes($string) {
		return str_replace("'","\'",str_replace("\'","'",$string));
	}


}