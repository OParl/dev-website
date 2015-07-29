<?php namespace App\Http\Controllers;

use App\Http\Requests;

class NewsController extends Controller
{
    public function index()
    {
      return view('base');
    }
}
