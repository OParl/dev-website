<?php namespace App\Http\Controllers;

class StaticPagesController extends Controller
{
  public function imprint()
  {
    return view('pages.imprint');
  }

  public function status()
  {
    return view('pages.status');
  }
}