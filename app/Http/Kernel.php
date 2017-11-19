<?php

namespace App\Http;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetApplicationLocale;
use App\Http\Middleware\TrackNonWebRequest;
use App\Http\Middleware\ValidateGitHubWebHook;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,

        VerifyCsrfToken::class,

        SetApplicationLocale::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'guest'        => RedirectIfAuthenticated::class,
        'hooks.github' => ValidateGitHubWebHook::class,
        'track'        => TrackNonWebRequest::class,
        'bindings'     => SubstituteBindings::class,
    ];
}
