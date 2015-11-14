<?php namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $title = 'Über OParl';

        return view('about.index', compact('title'));
    }
}
