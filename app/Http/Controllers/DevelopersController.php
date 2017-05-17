<?php

namespace App\Http\Controllers;

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
        $endpoints = collect(Yaml::parse($fs->get('live/endpoints.yml')))->sortBy('name');

        return view('developers.endpoints', [
            'title'     => trans('app.endpoints.title'),
            'endpoints' => $endpoints,
        ]);
    }
}
