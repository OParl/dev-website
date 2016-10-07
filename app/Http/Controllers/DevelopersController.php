<?php

namespace App\Http\Controllers;

class DevelopersController extends Controller
{
    public function index()
    {
        // this is still in development
        // for now redirect to spec view
        if (config('app.env') === 'production') {
            return redirect()->route('specification.index');
        }

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
