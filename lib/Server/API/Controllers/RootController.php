<?php

namespace OParl\Server\API\Controllers;

use App\Http\Controllers\Controller;

class RootController extends Controller
{
    public function index() {
        return redirect()->route('api.v1.system.index', ['format' => 'html']);
    }
}