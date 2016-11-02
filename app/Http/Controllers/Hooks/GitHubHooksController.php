<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 07/10/2016
 * Time: 18:29
 */

namespace App\Http\Controllers\Hooks;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ValidateGitHubWebHook;
use App\Jobs\GitHubPushJob;
use Illuminate\Http\Request;

class GitHubHooksController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateGitHubWebHook::class, ['except' => 'index']);
    }

    public function index()
    {
        return abort(400);
    }

    public function push(Request $request, $repository)
    {
        $allowedRepositories = collect(config('github.repositories'));

        if (!$allowedRepositories->contains($repository)) {
            return abort(400);
        }

        $ghEvent = $request->header('x-github-event');
        $payload = json_decode($request->input('payload'), true);

        switch ($ghEvent) {
            case 'pull_request':
                // update jobs are only necessary on PR merges


                if ($payload['action'] === 'closed' && $payload['merged']) {
                    $this->dispatch(new GitHubPushJob($repository, $payload));

                    return response()->json(['result' => 'Success.']);
                }

                return response()->json(['result' => 'No merge happened. Nothing to do.']);

            case 'push':
                $this->dispatch(new GitHubPushJob($repository, $payload));

                return response()->json(['result' => 'Success.']);

            case 'ping':
            default:
                return response()->json(['result' => \Inspiring::quote()]);
        }
    }
}