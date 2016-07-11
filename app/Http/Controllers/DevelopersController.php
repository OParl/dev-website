<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
}
