<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class ApiController extends Controller {

    /** GET CARDS **/
    public function getcards() {

        $params = $_GET;

        $cardModel = new Card();
        $cards = $cardModel->getCards($params);

        $data['html'] = (string) view('cards', array('cards' => $cards));
        $data['cards'] = $cards;
        return response()->json($data);
    }

    /** CREATE USER **/
    public function createuser() {

        $userModel = new User();
        $userId = $userModel->createUser();
        $data['success'] = true;
        $data['userId'] = $userId;

        return response()->json($data);
    }

    /** RATE: LIKE / DISLIKE EVENT **/
    public function rate() {

        $params = $_POST;
        $userEventModel = new Rating();
        $userEventModel->rate($params);
        $data['success'] = true;

        return response()->json($data);
    }

    /** GET RATINGS **/
    public function getratings() {

        $params = $_GET;
        $ratingModel = new Rating();
        $ratings = $ratingModel->getRatings($params);
        $data['success'] = true;
        $data['ratings'] = $ratings;

        return response()->json($data);
    }

}
