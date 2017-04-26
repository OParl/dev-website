<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 07/10/2016
 * Time: 18:29.
 */

namespace App\Http\Controllers\Hooks;

use App\Http\Middleware\ValidateGitHubWebHook;
use App\Jobs\GitHubPushJob;
use Illuminate\Http\Request;

class GitLabHooksController extends HooksController
{
    public function __construct()
    {

    }

    public function push(Request $request, $repository)
    {
        $allowedRepositories = collect(['grindhold-liboparl']);

        if (!$allowedRepositories->contains($repository)) {
            return abort(400);
        }

        return $request->input();
    }
}
