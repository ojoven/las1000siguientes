<?php

namespace App\Lib;

use Xinax\LaravelGettext\Facades\LaravelGettext;

class Functions {

	/** ARRAYS **/
	public static function getArrayWithIndexValues($array, $index) {

		$arrayIndexes = array();
		foreach ($array as $element) {
			$arrayIndexes[] = $element[$index];
		}

		return $arrayIndexes;
	}

	public static function strposArray($haystack, $needles, $offset = 0) {
		if (is_array($needles)) {
			foreach ($needles as $needle) {
				$pos = self::strposArray($haystack, $needle);
				if ($pos !== false) {
					return $pos;
				}
			}
			return false;
		} else {
			return strpos($haystack, $needles, $offset);
		}
	}

	/** LOG **/
	public static function log($message) {

		if (is_string($message)) {
			echo $message;
		} else {
			print_r($message);
		}

		echo PHP_EOL;
	}
}

