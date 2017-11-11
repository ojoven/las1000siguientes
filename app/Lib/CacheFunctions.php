<?php

namespace App\Lib;
use App\Lib\Functions;

class CacheFunctions {

	/** GET CACHE KEY FOR EVENT LIKE RATINGS **/
	public static function getCacheLikeRatings($wordId) {

		$cacheKey = 'likes_' . $wordId;
		return $cacheKey;
	}

}

