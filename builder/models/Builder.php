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

		$db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		foreach ($words as $word) {
			$definitions = $this->extractDefinitionsWord($word);
			foreach ($definitions as $definition) {
				echo $definition['definition'] . PHP_EOL;
				$definitionDB = $this->parseDefinitionDB($definition, $word);
				Functions::insertDB($db, 'definitions', $definitionDB);
			}
		}

	}

	public function parseDefinitionDB($definition, $word) {

		$definitionDB['word'] = $word;
		$definitionDB['definition'] = str_replace('‖ ', '', $definition['definition']);
		$definitionDB['examples'] = implode('||', $definition['examples']);
		$definitionDB['attributes'] = implode('||', $definition['attributes']);
		$definitionDB['featured'] = $definition['featured'];
		$definitionDB['position'] = $definition['position'];
		$definitionDB['created_at'] = date('Y-m-d H:i:s');
		$definitionDB['updated_at'] = date('Y-m-d H:i:s');

		return $definitionDB;
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
				$completeDefinition['featured'] = ($index <= 2) ? true : false; // At first, we feature only the first 3 definitions
				$definitions[] = $completeDefinition;
			}
		}

		return $definitions;
	}

	/** EXAMPLES **/
	public function saveExampleHTMLs() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();

		// Now we check if the HTMLs from the different sources are already stored
		foreach ($words as $word) {

			$url = 'https://glosbe.com/gapi/translate?from=spa&dest=eng&phrase=' . urlencode($word) . '&format=json&tm=true';

			try {
				$examplesHtmlString = ExamplesHtml::exampleHTMLExists($word) ?
					ExamplesHtml::getExampleHTML($word) : ExamplesHtml::saveExampleHTML($word, $url);
			} catch (Exception $e) {
				Functions::log($word . ': ' . $e->getMessage());
			}
		}

	}

	public function saveExamples() {

		$wordModel = new Word();
		$words = $wordModel->getListWords();

		$db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		foreach ($words as $word) {
			$examples = $this->extractExamplesWord($word);
			$count = count($examples);
			foreach ($examples as $index => $example) {
				echo $example . PHP_EOL;
				$exampleDB = $this->parseExampleDB($example, $word, $index, $count);
				Functions::insertDB($db, 'examples', $exampleDB);
			}
		}

	}

	public function extractExamplesWord($word) {

		$examplesJsonBase = ROOT . "/htmls/glosbe/";
		$examples = [];

		$exampleJson = $examplesJsonBase . $word . '.json';
		if (file_exists($exampleJson)) {

			$json = file_get_contents($exampleJson);
			$array = json_decode($json, true);

			foreach ($array['examples'] as $example) {

				$examples[] = $example['first'];
			}
		}

		// Sort the examples by length
		usort($examples, function($a, $b) {
			return strlen($b) - strlen($a);
		});

		$examples = array_reverse($examples);

		return $examples;

	}

	public function parseExampleDB($example, $word, $index, $count) {

		$featured = ($index == floor($count/3));

		$exampleDb['word'] = $word;
		$exampleDb['example'] = $example;
		$exampleDb['featured'] = $featured;
		$exampleDb['created_at'] = date('Y-m-d H:i:s');
		$exampleDb['updated_at'] = date('Y-m-d H:i:s');

		return $exampleDb;
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