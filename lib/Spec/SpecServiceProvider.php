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

    $this->commands([
      'oparl.specification.commands.delete_builds'
    ]);
  }

  public function provides()
  {
    return [
      'oparl.specification.commands.delete_builds'
    ];
  }
}