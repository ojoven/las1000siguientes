<?php
/** BUILDER **/
class Builder {

	/** WORDS **/
	public function saveWordsToDatabase() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();
		foreach ($words as $word) {


		}

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