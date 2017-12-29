<?php
/** EXAMPLES HTMLS **/
class ExamplesHtml {

	public static function exampleHTMLExists($word) {

		$pathToExampleHtml = self::buildPathToExampleHTML($word);
		return file_exists($pathToExampleHtml);
	}

	public static function getExampleHTML($word) {

		$pathToExampleHtml = self::buildPathToExampleHTML($word);
		$exampleHtml = file_get_contents($pathToExampleHtml);
		Functions::log('Retrieve ' . $word);

		return $exampleHtml;
	}

	public static function saveExampleHTML($word, $urlExampleHtml) {

		usleep(300);

		$pathToExampleHtml = self::buildPathToExampleHTML($word);
		$exampleHtml = file_get_contents($urlExampleHtml);

		if (!$exampleHtml) throw new Exception("The file couldn't be correctly retrieved");

		$response = file_put_contents($pathToExampleHtml, $exampleHtml);
		if (!$response) throw new Exception("The file couldn't be correctly stored");

		Functions::log('Save ' . $word);

		return $exampleHtml;

	}

	public static function buildPathToExampleHTML($word) {

		$pathToExampleHtml = ROOT . '/htmls/glosbe/' . $word . '.json';
		return $pathToExampleHtml;
	}

}