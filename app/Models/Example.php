<?php

namespace App\Models;
use App\Lib\Functions;
use App\Lib\CacheFunctions;
use Illuminate\Database\Eloquent\Model;

class Example extends Model {

	public function getExamplesWord($word) {

		$examples = self::where('word', $word['word'])->get()->toArray();
		return $examples;
	}

}
