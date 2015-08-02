<?php namespace App\Http\Controllers;

use App\Http\Requests\VersionSelectRequest;
use App\Jobs\CreateBuild;
use OParl\Spec\VersionRepository;

class DownloadsController extends Controller
{
  protected $versions = null;

  public function __construct(VersionRepository $versions)
  {
    $this->versions = $versions;
  }

  public function index(\Illuminate\Http\Request $request)
  {
    return view('downloads.index', ['versions' => $this->versions, 'title' => 'Downloads']);
  }

  public function latest($extension)
  {
    return redirect(null, 302)->route('downloads.provide', [
      $this->versions->latest()->getHash() ,
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
    if (!$this->versions->isAvailable($request->input('version')))
    {
      // fire fetch job
      $this->dispatch(new CreateBuild(
        $request->input('version'),
        $request->input('email'),
        $request->input('format')
      ));

      // redirect to success page
      return redirect()->route('downloads.success')->with('email', $request->input('email'));
    }

    // redirect to download link
    return redirect(null, 302)->route('downloads.provide', [
      $request->input('version'),
      $request->input('format')
    ]);
  }

  public function success()
  {
    return view('downloads.success', ['title' => 'Anfrage bestätigt - Downloads']);
  }
}