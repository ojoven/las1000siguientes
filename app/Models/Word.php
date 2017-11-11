<?php

namespace App\Models;
use App\Lib\Functions;
use App\Lib\CacheFunctions;
use App\Lib\RenderFunctions;
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
		//$words = $allWords[array_rand($allWords, $this->numWordsPage)]
		$words = $allWords;

		return $words;
	}

	// Complete Words
	public function completeWords($words) {

		foreach ($words as &$word) {

		}

		return $words;
	}

}
