<?php

namespace App\Http\Middleware;

use Closure;

/**
 * This middleware can be used to track
 * non-web requests (which do not deliver html)
 * like API calls or Download routes.
 */
class TrackNonWebRequest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (class_exists('\PiwikTracker')) {
            $tracker = new \PiwikTracker(config('piwik.siteId'), config('piwik.url'));
            try {
                $tracker->doTrackPageView('demoapi:'.$request->getRequestUri());
            } catch (\Exception $e) {
                \Log::warning('Piwik doesn\'t appear to be configured properly.');
            }
        }

        return $response;
    }
}
