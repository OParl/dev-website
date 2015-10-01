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

    $this->app->singleton(
      'oparl.specification.commands.request_build_bk',
      Commands\RequestSpecificationBuildCommand::class
    );

    $this->app->singleton(
      'oparl.specification.commands.list_builds',
      Commands\ListSpecificationBuildsCommand::class
    );

    $this->commands([
      'oparl.specification.commands.delete_builds',
      'oparl.specification.commands.update_builds_gh',
      'oparl.specification.commands.request_build_bk',
      'oparl.specification.commands.list_builds',
    ]);
  }

  public function provides()
  {
    return [
      'oparl.specification.commands.delete_builds',
      'oparl.specification.commands.update_builds_gh',
      'oparl.specification.commands.request_build_bk',
      'oparl.specification.commands.list_builds',
    ];
  }
}