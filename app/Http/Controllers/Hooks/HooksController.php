<?php

namespace App\Http\Controllers\Hooks;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HooksController extends Controller
{
    public function index()
    {
        return abort(400);
    }
}
