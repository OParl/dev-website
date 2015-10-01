<?php namespace OParl\Spec;

use \Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class SpecServiceProvider extends IlluminateServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('VersionRepository', VersionRepository::class, true);
    $this->app->bind('LiveCopyRepository', LiveCopyRepository::class, true);
    $this->app->bind('SpecificationBuildRepository', BuildRepository::class, true);

    $this->app->singleton(
      'oparl.specification.commands.delete_builds',
      Commands\DeleteSpecificationBuildsCommand::class
    );

    $this->app->singleton(
      'oparl.specification.commands.update_builds_gh',
      Commands\UpdateSpecificationBuildDataFromGitHubCommand::class
    );

    $this->commands([
      'oparl.specification.commands.delete_builds',
      'oparl.specification.commands.update_builds_gh'
    ]);
  }

  public function provides()
  {
    return [
      'oparl.specification.commands.delete_builds',
      'oparl.specification.commands.update_builds_gh'
    ];
  }
}