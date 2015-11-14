<?php namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $title = 'Über OParl';

        return view('about.index', compact('title'));
    }

    public function redirectIndex()
    {
        return redirect()->route('about.index');
    }

    public function councils()
    {
        $title = 'OParl für Kommunen';

        return view('about.councils', compact('title'));
    }

    public function developers()
    {
        $title = 'OParl für Entwickler';

        return view('about.developers', compact('title'));
    }

    public function ris()
    {
        $title = 'OParl für RIS-Hersteller';

        return view('about.ris', compact('title'));
    }
}
