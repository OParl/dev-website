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

  public function getFile($version, $extension)
  {
    $file = null;

    switch ($extension)
    {
      case 'docx':
      case 'pdf':
      case 'epub':
      case 'odt':
      case 'html':
      case 'txt':
        $file = storage_path('app/versions/'.$version.'/out/OParl-1.0-draft.'.$extension);
        break;

      case 'zip':
      case 'tar.gz':
      case 'tar.bz2':
        $file = storage_path('app/versions/'.$version.'/OParl-1.0-draft.'.$extension);
    }

    return response()->download(new \SplFileInfo($file), basename($file));
  }
}