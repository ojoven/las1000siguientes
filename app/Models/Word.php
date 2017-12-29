<?php

namespace App\Models;
use App\Lib\Functions;
use App\Lib\CacheFunctions;
use Illuminate\Database\Eloquent\Model;

class Word extends Model {

	protected $numWordsPage = 10;

	// All Words
	public function getAllWords() {

		$words = self::get()->toArray();
		return $words;
	}

	// Get Words
	public function getWords() {

		$allWords = $this->getAllWords();
		shuffle($allWords);
		$words = array_slice($allWords, 0, $this->numWordsPage);
		$words = $this->completeWords($words);

		return $words;
	}

	// Complete Words
	public function completeWords($words) {

		$definitionModel = new Definition();
		$exampleModel = new Example();

		foreach ($words as &$word) {
			$word['definitions'] = $definitionModel->getDefinitionsWord($word);
			$word['examples'] = $exampleModel->getExamplesWord($word);
		}

		return $words;
	}

}
