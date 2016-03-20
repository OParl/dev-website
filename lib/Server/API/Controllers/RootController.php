<?php

namespace OParl\Server\API\Controllers;

use App\Http\Controllers\Controller;

class RootController extends Controller
{
    public function index() {
        return view('server::overview');
    }
}