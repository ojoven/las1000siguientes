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
			//array('source' => 'wikcionario', 'url' => 'https://es.wiktionary.org/wiki/', 'wait' => false),
			array('source' => 'rae', 'url' => 'http://dle.rae.es/srv/search?m=30&w=', 'wait' => true)
		];

		return $sources;
	}

	/** DEFINITIONS **/
	public function saveDefinitions() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();
		foreach ($words as $word) {
			$definitions = $this->extractDefinitionsWord($word);
			if (!$definitions) {
				echo $word . PHP_EOL;
			}
		}

	}

	public function extractDefinitionsWord($word) {

		$wordsHtmlBase = ROOT . "/htmls/rae/";
		$definitions = [];

		$wordHtml = $wordsHtmlBase . $word . '.html';
		if (file_exists($wordHtml)) {

			$html = file_get_html($wordHtml);
			foreach ($html->find('p.j') as $index => $definition) {

				$cleanDefinition = $definition->plaintext;
				list($position, $cleanDefinition) = explode('. ', $cleanDefinition, 2);

				$definitionAttributes = [];
				foreach ($definition->find('abbr') as $attribute) {
					$definitionAttributes[] = $attribute->plaintext;
					$cleanDefinition = str_replace($attribute->plaintext, '', $cleanDefinition);
				}

				$definitionExamples = [];
				foreach ($definition->find('span.h') as $example) {
					$definitionExamples[] = $example->plaintext;
					$cleanDefinition = str_replace($example->plaintext, '', $cleanDefinition);
				}

				// Clean notes
				foreach ($definition->find('sup') as $note) {
					$cleanDefinition = str_replace($note->plaintext, '', $cleanDefinition);
				}

				$cleanDefinition = ucfirst(trim($cleanDefinition));
				$completeDefinition['definition'] = $cleanDefinition;
				$completeDefinition['position'] = $position;
				$completeDefinition['examples'] = $definitionExamples;
				$completeDefinition['attributes'] = $definitionAttributes;
				$completeDefinition['featured'] = ($index === 0) ? true : false; // At first, we feature only the first definition
				$definitions[] = $completeDefinition;
			}
		}

		return $definitions;
	}

	/** AUXILIAR **/
	public function cleanHtmlFiles() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();

		$htmlsFolder = ROOT . '/htmls/rae';

		foreach(glob($htmlsFolder.'/*.*') as $file) {

			$word = str_replace($htmlsFolder . '/', '', $file);
			$word = str_replace('.html', '', $word);

			if (!in_array($word, $words)) {
				echo $word . PHP_EOL;
				//unlink($htmlsFolder . '/' . $word . '.html');
			}
		}
	}

}