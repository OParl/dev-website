<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class RedirectController {
    public function fuzzy(Request $request, Router $router) {
        $redirectRoute = collect($router->getRoutes()->get(strtoupper($request->method())))
            ->filter(function (Route $route) use ($request) {
                $host = strtolower(parse_url($request->getUri(), PHP_URL_HOST));

                return !$route->matches($request) || strtolower($route->domain()) !== $host;
            })
            ->sort(function (Route $routeA, Route $routeB) use ($request) {
                $requestPath = substr(parse_url($request->getUri(), PHP_URL_PATH), 1);

                $routeAUriLevenshtein = levenshtein($routeA->uri(), $requestPath);
                $routeBUriLevenshtein = levenshtein($routeB->uri(), $requestPath);

                if ($routeAUriLevenshtein > $routeBUriLevenshtein) return 1;
                if ($routeAUriLevenshtein < $routeBUriLevenshtein) return -1;

                return 0;
            })
            ->first();

        return redirect($redirectRoute->uri());
    }
}