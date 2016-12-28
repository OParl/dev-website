<?php

namespace App\Http\Middleware;

use Closure;

class TrackAPICall
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
        /* @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (class_exists('\PiwikTracker')) {
            $tracker = new \PiwikTracker(config('piwik.siteId'), config('piwik.url'));
            $tracker->doTrackPageView('demoapi:' . $request->getRequestUri());
        }

        return $response;
    }
}
