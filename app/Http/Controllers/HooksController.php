<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;

class HooksController extends Controller
{
  public function specChange(Request $request)
  {
    // GitHub update hook, load new version hashes
    $json = $request->json();

    switch ($request->header('x-github-event'))
    {
      case 'pull_request': break;
      case 'push': break;

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