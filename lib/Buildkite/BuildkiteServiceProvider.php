<?php namespace EFrane\Buildkite;

use Illuminate\Support\ServiceProvider;

class BuildkiteServiceProvider extends ServiceProvider
{
  protected $defer = true;

  public function provides()
  {
    return ['buildkite'];
  }

  public function register()
  {
    $this->app->bind('buildkite', function () {
      return new Buildkite(config('services.buildkite.access_token'));
    });
  }
}
