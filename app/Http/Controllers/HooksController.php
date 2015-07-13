<?php namespace App\Http\Controllers;

use App\Jobs\UpdateLiveCopy;
use App\Jobs\UpdateVersionHashes;
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
        break;

      case 'push':
        // just initiate spec and version updates
        $this->dispatch(new UpdateLiveCopy());
        $this->dispatch(new UpdateVersionHashes());
        break;

      case 'ping':
      default:
        return response()->json(['result' => Inspiring::quote()]);
    }

    return response()->json();
  }

  public function addVersion()
  {
    // Buildkite build finish hook, receive a packed fully built version
  }
}