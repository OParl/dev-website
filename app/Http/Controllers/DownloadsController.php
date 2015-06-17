<?php namespace App\Http\Controllers;

use App\Http\Requests\VersionSelectRequest;
use OParl\Spec\VersionRepository;

class DownloadsController extends Controller
{
  protected $versions = null;

  public function __construct(VersionRepository $versions)
  {
    $this->versions = $versions;
  }

  public function index()
  {
    return view('downloads.index', ['versions' => $this->versions]);
  }

  public function latest()
  {
    return "";
  }

  public function selectVersion(VersionSelectRequest $request)
  {
    //
  }
}