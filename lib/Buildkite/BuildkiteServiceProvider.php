<?php

namespace EFrane\Buildkite;

use Illuminate\Support\ServiceProvider;

class BuildkiteServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function provides()
    {
        return ['Buildkite'];
    }

    public function register()
    {
        $this->app->bind('Buildkite', function () {
            return new Buildkite(config('services.buildkite.access_token'));
        });
    }
}
