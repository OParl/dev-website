<?php namespace App\Http\Controllers;

use OParl\Spec\VersionRepository;

class DownloadsController extends Controller
{
  public function index(VersionRepository $versions)
  {
    return view('downloads.index', compact('versions'));
  }
}