<?php

namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use OParl\Spec\Commands\UpdateSpecificationCommand;

class SpecServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
           UpdateSpecificationCommand::class
        ]);
    }
}
