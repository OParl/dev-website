<?php namespace OParl\Spec;

use \Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('VersionRepository', 'OParl\Spec\VersionRepository', true);
    $this->app->bind('LiveCopyRepository', 'OParl\Spec\LiveCopyRepository', true);
  }

}