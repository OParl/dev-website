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
    // GitHub update hook, load new version hashes
    $json = json_decode($request->input('payload'), true);

    switch ($request->header('x-github-event'))
    {
      case 'pull_request': break;
      case 'push':
        // just initiate spec and version updates
        $this->dispatch(new UpdateLiveCopy());
        $this->dispatch(new UpdateVersionHashes());
        break;

      case 'ping':
      default:
        return response()->json(['result' => Inspiring::quote()]);
    }
  }

  public function addVersion()
  {
    // Buildkite build finish hook, receive a packed fully built version
  }
}