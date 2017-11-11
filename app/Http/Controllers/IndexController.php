<?php

namespace App\Http\Controllers;

use App\Lib\Functions;
use App\Http\Requests;

// Models

class IndexController extends Controller {

    public function index() {

        return view('index');
    }

}
