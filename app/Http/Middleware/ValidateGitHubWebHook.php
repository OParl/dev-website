<?php

namespace App\Http\Middleware;

use Closure;

class ValidateGitHubWebHook
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

      // is application/x-www-form-urlencoded
      if (!$request->header('content-type', 'application/x-www-form-urlencoded')) return $errorResponse;

      // appears to be from github
      if (!starts_with($request->header('user-agent'), 'GitHub-Hookshot/')) return $errorResponse;
      if (!$request->header('x-github-event')) return $errorResponse;

      // TODO: check digest (https://developer.github.com/webhooks/securing/)
      // NOTE: the secret is stored in env('GITHUB_WEBHOOK_SECRET')

      return $next($request);
    }
}
