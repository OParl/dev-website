<?php

namespace App\Http\Controllers;

use App\Model\Endpoint;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;

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
}
