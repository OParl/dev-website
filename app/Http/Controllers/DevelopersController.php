<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DevelopersController extends Controller
{
    public function index()
    {
        return view('developers.index');
    }
}
