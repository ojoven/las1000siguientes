<?php
/** WORDS **/
class Word {

	public function getListWords() {

		$words = $this->getListWordsFromFile();
		$words = $this->cleanWords($words);
		$words = $this->filterWords($words);

		return $words;
	}

	public function getListWordsFromFile() {

		$wordsFilePath = ROOT . '/data/words';
		$wordsString = file_get_contents($wordsFilePath);
		$words = explode(PHP_EOL, $wordsString);

		return $words;

	}

	public function filterWords($words) {

		$words = array_unique($words);
		return $words;
	}

	public function cleanWords($words) {

		foreach ($words as &$word) {

			$word = strtolower($word);
			$word = trim($word);
		}

		return $words;
	}

}