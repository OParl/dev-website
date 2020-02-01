<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class ValidateGitHubWebHook
{
    /**
     * Handle an incoming GitHub Webhook request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $errorResponse = response()->json(['error' => 'We kindly apologize for the inconvenience but you are not allowed here.']);

        // is application/x-www-form-urlencoded
        if (!$request->header('content-type', 'application/x-www-form-urlencoded')) {
            return $errorResponse;
        }

        // appears to be from github
        if (!Str::startsWith($request->header('user-agent'), 'GitHub-Hookshot/')) {
            return $errorResponse;
        }
        if (!$request->header('x-github-event')) {
            return $errorResponse;
        }

        // check digest (https://developer.github.com/webhooks/securing/)
        $hmac = hash_hmac('sha1', $request->getContent(), env('GITHUB_WEBHOOK_SECRET'));
        $hmacFormatted = 'sha1='.$hmac;
        if ($hmacFormatted !== $request->header('x-hub-signature')) {
            return $errorResponse;
        }

        return $next($request);
    }
}
