<?php
/**
 * @copyright 2017
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class SetApplicationLocale
{
    public function handle(Request $request, \Closure $next)
    {
        $locale = config('app.locale');

        if ($request->session()->has('user.locale')) {
            $locale = $request->session()->get('user.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
