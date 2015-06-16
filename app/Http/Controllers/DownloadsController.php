<?php namespace App\Http\Controllers;

class DownloadsController extends Controller
{
  public function index()
  {
    return view('downloads.index');
  }
}