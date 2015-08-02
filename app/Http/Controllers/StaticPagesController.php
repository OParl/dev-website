<?php namespace App\Http\Controllers;

class StaticPagesController extends Controller
{
  public function imprint()
  {
    return view('pages.imprint', ['title' => 'Impressum']);
  }

  public function status()
  {
    return view('pages.status', ['title' => 'Status']);
  }

  public function about()
  {
    return view('pages.about', ['title' => 'Über OParl']);
  }
}