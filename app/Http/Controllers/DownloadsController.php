<?php namespace App\Http\Controllers;

use App\Http\Requests\VersionSelectRequest;
use App\Jobs\CreateBuild;
use OParl\Spec\BuildRepository;
use OParl\Spec\VersionRepository;

class DownloadsController extends Controller
{
  public function index(BuildRepository $buildRepository)
  {
    return view('downloads.index', [
      'builds' => $buildRepository->getLatest(15),
      'title' => 'Downloads'
    ]);
  }

  public function latest($extension, BuildRepository $buildRepository)
  {
    return redirect(null, 302)->route('downloads.provide', [
      $buildRepository->getLatest()->hash,
      $extension
    ]);
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

    if ($this->versions->isLatest($version))
    {
      $filename = basename($file);
    } else
    {
      $basename = basename($file, ".{$extension}");
      $filename = sprintf('%s-%s.%s', $basename, $version, $extension);
    }

    return response()->download(new \SplFileInfo($file), $filename);
  }

  public function selectVersion(VersionSelectRequest $request)
  {
    // redirect to download link
    return redirect(null, 302)->route('downloads.provide', [
      $request->input('version'),
      $request->input('format')
    ]);
  }
}