<?php

namespace App\Models;
use App\Lib\Functions;
use App\Lib\CacheFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Rating extends Model {

	protected $fillable = ['userId', 'wordId', 'rating'];

	/** ADD RATING **/
	public function rate($params) {

		// If already rated
		if ($previousRating = $this->hasTheUserRatedPreviouslyTheWord($params['userId'], $params['wordId'])) {

			// We update the previous rating
			$previousRating->rating = $params['rating'];
			$previousRating->save();
		} else {

			$rating = new self();
			$rating->user_id = $params['userId'];
			$rating->word_id = $params['wordId'];
			$rating->rating = $params['rating'];
			$rating->save();

		}

	}

	public function hasTheUserRatedPreviouslyTheWord($userId, $wordId) {

		return self::where('user_id', '=', $userId)->where('word_id', '=', $wordId)->first();
	}

	/** GET RATINGS **/
	public function getRatings($params, $rating = false) {

		// We only will serve ratings of words that are currently available (no past words)
		$wordModel = new Word();
		$words = $wordModel->getAllWords();
		$wordIds = Functions::getArrayWithIndexValues($words, 'id');

		// Get words rated by user
		// If rating, we will filter by rating, too
		if ($rating) {
			$ratings = self::where('user_id', '=', $params['user_id'])->where('rating', '=', $rating)->orderBy('created_at', 'desc')->get()->toArray();
		} else {
			$ratings = self::where('user_id', '=', $params['user_id'])->orderBy('created_at', 'desc')->get()->toArray();
		}

		// Now we filter the words with the currently available
		$finalRatings = array();

		foreach ($ratings as $rating) {

			if (in_array($rating['word_id'], $wordIds)) {
				$finalRatings[] = $rating;
			}
		}

		// We prepare them for front end rendering
		$finalRatings = $this->parseRatings($finalRatings);

		return $finalRatings;
	}

	public function parseRatings($ratings) {

		$parsedRatings = array();
		foreach ($ratings as $rating) {

			$parsedRating = array(
				'wordId' => $rating['word_id'],
				'rating' => $rating['rating'],
			);

			$parsedRatings[] = $parsedRating;
		}

		return $parsedRatings;
	}

	// GET LIKES
	public function getLikesWord($word) {

		return Cache::remember(CacheFunctions::getCacheLikeRatings($word['id']), 60, function() use ($word) {

			$wordIds = array($word['id']);

			// We'll get the likes for the translated version of the word
			$wordModel = new Word();
			$translatedWord = $wordModel->getTranslatedWord($word);
			if ($translatedWord) {
				$wordIds[] = $translatedWord['id'];
			}

			return self::where('rating', '=', '1')->whereIn('word_id', $wordIds)->get()->toArray();
		});

	}

}