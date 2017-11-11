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

}