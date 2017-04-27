<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 07/10/2016
 * Time: 18:29.
 */

namespace App\Http\Controllers\Hooks;

use App\Http\Middleware\ValidateGitHubWebHook;
use App\Jobs\GitLabPipelineJob;
use Illuminate\Http\Request;

class GitLabHooksController extends HooksController
{
    public function __construct()
    {
        $this->middleware(ValidateGitHubWebHook::class, ['except' => 'index']);
    }

    public function push(Request $request, $repository)
    {
        $allowedRepositories = collect(['grindhold-liboparl']);

        if (!$allowedRepositories->contains($repository)) {
            return abort(400);
        }

        $glEvent = $request->header('x-gitlab-event');

        if (is_null($glEvent)) {
            return abort(400);
        }

        $payload = json_decode($request->request, true);

        switch ($glEvent) {
            case 'Pipeline Hook':
                $this->dispatch(new GitLabPipelineJob($payload));

                return response()->json(['result' => 'Success.']);
                break;

            default:
                return response()->json(['result' => \Inspiring::quote()]);
        }
    }
}
