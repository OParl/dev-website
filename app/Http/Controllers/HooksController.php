<?php namespace App\Http\Controllers;

use App\Jobs\UpdateLiveCopy;
use App\Jobs\UpdateVersionHashes;

use App\Http\Requests\VersionUpdateRequest;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;

class HooksController extends Controller
{
  use DispatchesJobs;

  public function specChange(Request $request)
  {
    switch ($request->header('x-github-event'))
    {
      case 'pull_request':
        // update jobs are only necessary on PR merges
        $json = json_decode($request->input('payload'), true);

        if ($json['action'] == 'closed' && $json['merged'])
        {
          $this->dispatch(new UpdateLiveCopy());
          $this->dispatch(new UpdateVersionHashes());
        }

        return response()->json(['result' => 'Scheduled updates.']);

      case 'push':
        // just initiate spec and version updates
        $this->dispatch(new UpdateLiveCopy());
        $this->dispatch(new UpdateVersionHashes());

        return response()->json(['result' => 'Scheduled updates.']);

      case 'ping':
      default:
        return response()->json(['result' => Inspiring::quote()]);
    }
  }

  public function addVersion(VersionUpdateRequest $request, Filesystem $fs, $key, $hash)
  {
    try
    {
      $path = 'versions/'.$hash.'/';
      $fs->makeDirectory($path, '0755', true, true);
      $fs->cleanDirectory($path);

      foreach ($request->file() as $file)
        $file->move(storage_path('app/'. $path), $file->getClientOriginalName());

      chdir(storage_path('app/'.$path));
      exec('tar -xzf *.tar.gz');

      return response()->json(['hash' => $hash, 'success' => true]);
    } catch (\Exception $e)
    {
      return response()->json(['hash' => $hash, 'success' => false]);
    }
  }
}