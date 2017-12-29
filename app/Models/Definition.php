<?php

namespace App\Models;
use App\Lib\Functions;
use App\Lib\CacheFunctions;
use Illuminate\Database\Eloquent\Model;

class Definition extends Model {

	public function getDefinitionsWord($word) {

		$definitions = self::where('word', $word['word'])->get()->toArray();
		return $definitions;
	}

}
