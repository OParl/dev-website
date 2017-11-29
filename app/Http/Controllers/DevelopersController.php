<?php

namespace App\Http\Controllers;

use App\Model\Endpoint;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class DevelopersController extends Controller
{
    public function index()
    {
        return view('developers.index', [
            'title' => trans('app.developers.title'),
        ]);
    }

    public function contact()
    {
        return view('developers.contact', [
            'title' => trans('app.contact.title'),
        ]);
    }

    public function redirectToIndex()
    {
        return redirect()->route('developers.index');
    }

    public function endpoints(Filesystem $fs)
    {
        $endpoints = Endpoint::all();

        return view('developers.endpoints', [
            'title'     => trans('app.endpoints.title'),
            'endpoints' => $endpoints,
        ]);
    }
}
