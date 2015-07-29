<?php namespace App\Http\Controllers;

class StaticPagesController extends Controller
{
  public function imprint()
  {
    return view('pages.imprint');
  }
}