<?php

namespace App\Models;

use App\Models\Word;
use App\Lib\Functions;
use App\Lib\CacheFunctions;
use Illuminate\Database\Eloquent\Model;

class Card extends Model {

	/** GET **/
	public function getCards($params) {

		// GET WORDS
		$wordModel = new Word();
		$words = $wordModel->getWords($params);

		return $words;
	}

}
