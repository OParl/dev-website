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

      // is application/json
      if (!$request->isJson()) return $errorResponse;

      // appears to be from github
      if (!starts_with($request->header('user-agent'), 'GitHub-Hookshoot')) return $errorResponse;
      if (!$request->header('x-github-event')) return $errorResponse;

      // secret equals env('GITHUB_WEBHOOK_SECRET')
      try
      {
        $json = $request->json();
        if ($json['hook']['config']['secret'] !== env('GITHUB_WEBHOOK_SECRET')) return $errorResponse;
      } catch (\Exception $e)
      {
        return $errorResponse;
      }

      return $next($request);
    }
}
