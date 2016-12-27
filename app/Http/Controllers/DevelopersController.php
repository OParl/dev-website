<?php

namespace App\Http\Controllers;

class DevelopersController extends Controller
{
    public function index()
    {
        return view('developers.index');
    }

    public function contact()
    {
        return view('developers.contact');
    }

    public function redirectToIndex()
    {
        return redirect()->route('developers.index');
    }
}
