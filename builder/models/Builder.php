<?php
require_once __DIR__. '/../config.php';

/** BUILDER **/
class Builder {

	/** WORDS **/
	public function saveWordsToDatabase() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();

		$db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		foreach ($words as $word) {
			$wordDb = $this->parseWordForDatabase($word);
			Functions::insertDB($db, 'words', $wordDb);
		}

	}

	public function parseWordForDatabase($word) {

		$wordDb['word'] = $word;
		return $wordDb;
	}

	/** HTMLS **/
	public function saveHtmlWords() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();
		$sources = $this->_getSources();

		// Now we check if the HTMLs from the different sources are already stored
		foreach ($words as $word) {

			foreach ($sources as $source) {

				try {
					$wordHtmlString = WordHtml::wordHTMLExists($word, $source['source']) ?
						WordHtml::getWordHTML($word, $source['source']) : WordHtml::saveWordHTML($word, $source['source'], $source['url'], $source['wait']);
				} catch (Exception $e) {
					Functions::log($word . ': ' . $e->getMessage());
				}
			}

		}

	}

	private function _getSources() {

		$sources = [
			array('source' => 'wikcionario', 'url' => 'https://es.wiktionary.org/wiki/', 'wait' => false),
			array('source' => 'rae', 'url' => 'http://dle.rae.es/srv/search?m=30&w=', 'wait' => true)
		];

		return $sources;
	}

}