<?php
/** WORD HTMLS **/
class WordHtml {

	public static function wordHTMLExists($word, $source) {

		$pathToWordHtml = self::buildPathToWordHTML($word, $source);
		return file_exists($pathToWordHtml);
	}

	public static function getWordHTML($word, $source) {

		$pathToWordHtml = self::buildPathToWordHTML($word, $source);
		$wordHtml = file_get_contents($pathToWordHtml);
		Functions::log('Retrieve ' . $word);

		return $wordHtml;
	}

	public static function saveWordHTML($word, $source, $urlBaseSource, $sourceWait = false) {

		$pathToWordHtml = self::buildPathToWordHTML($word, $source);
		$urlWordHtml = $urlBaseSource . $word;

		$wordHtml = $sourceWait ? self::saveHTMLAfterJavascriptLoaded($urlWordHtml) : file_get_contents($urlWordHtml);

		if (!$wordHtml) throw new Exception("The file couldn't be correctly retrieved");

		$response = file_put_contents($pathToWordHtml, $wordHtml);
		if (!$response) throw new Exception("The file couldn't be correctly stored");

		Functions::log($source . ': Save ' . $word);

		return $wordHtml;

	}

	public static function buildPathToWordHTML($word, $source) {

		$pathToWordHtml = ROOT . '/htmls/' . $source . '/' . $word . '.html';
		return $pathToWordHtml;
	}

	public static function saveHTMLAfterJavascriptLoaded($url) {

		$phantom_script = ROOT . '/lib/phantomOpenURL.js';
		$command = 'phantomjs ' . $phantom_script . ' "' . $url . '"';
		$response = shell_exec($command);
		return $response;
	}
}