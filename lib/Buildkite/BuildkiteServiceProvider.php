<?php namespace EFrane\Buildkite;

use Illuminate\Support\ServiceProvider;

class BuildkiteServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->provides('efrane/buildkite');

    $this->app->bind('buildkite', 'EFrane\Buildkite\Buildkite');
  }
}