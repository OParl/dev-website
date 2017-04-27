<?php

namespace App\Http\Middleware;

use Closure;

class ValidateGitLabWebHook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $errorResponse = response()->json(['error' => 'We kindly apologize for the inconvenience but you are not allowed here.']);

        // appears to be from gitlab
        if (!$request->header('x-gitlab-event')) {
            return $errorResponse;
        }

        // check token (https://docs.gitlab.com/ce/user/project/integrations/webhooks.html#secret-token)
        $token = config('services.gitlab.token');

        if ($token !== $request->header('x-gitlab-token')) {
            return $errorResponse;
        }

        return $next($request);
    }
}
