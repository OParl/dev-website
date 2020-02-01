<?php

namespace App\Http\Middleware;

use Closure;
use MatomoTracker;
use Psr\Log\LoggerInterface;

/**
 * This middleware can be used to track
 * non-web requests (which do not deliver html)
 * like API calls or Download routes.
 */
class TrackNonWebRequest
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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

        $tracker = new MatomoTracker(config('piwik.siteId'), config('piwik.url'));

        try {
            $tracker->doTrackPageView('demoapi:'.$request->getRequestUri());
        } catch (\Exception $e) {
            $this->logger->warning('Piwik doesn\'t appear to be configured properly.');
        }

        return $response;
    }
}
