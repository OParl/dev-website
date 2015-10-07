<?php namespace App\Http\Controllers;

use App\Http\Requests\VersionSelectRequest;
use OParl\Spec\BuildRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
      $buildRepository->getLatest()->short_hash,
      $extension
    ]);
  }

  public function getFile($short_hash, $extension, BuildRepository $buildRepository)
  {
    // TODO: fix downloads
    $build = $buildRepository->getWithShortHash($short_hash);

    $file = null;

    if (in_array($extension, ['zip', 'tar.gz', 'tar.bz']))
    {
      $property = sprintf('%s_archive_storage_path', str_replace('.', '_' ,$extension));

      $file = new \SplFileInfo($build->{$property});
    }

    if (!$file->isFile())
    {
      abort(404, 'Die angefragte Datei wurde auf diesem Server nicht gefunden.');
    } else
    {
      // TODO: filenames
      return response()->download($file);
    }
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