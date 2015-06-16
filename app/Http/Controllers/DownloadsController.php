<?php namespace App\Http\Controllers;

class DownloadsController extends Controller
{
  public function index()
  {
    $versions = app('VersionRepository');

    return view('downloads.index', compact('versions'));
  }
}