<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

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

    public function endpoints(Filesystem $fs)
    {
        $endpoints = Yaml::parse($fs->get('live/endpoints.yml'));

        return view('developers.endpoints', compact('endpoints'));
    }
}
