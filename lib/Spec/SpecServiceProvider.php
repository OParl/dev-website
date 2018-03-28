<?php

namespace OParl\Spec;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use OParl\Spec\Commands\InitializeCommand;
use OParl\Spec\Commands\InitializeSchemaCommand;
use OParl\Spec\Commands\UpdateDownloadsCommand;
use OParl\Spec\Commands\UpdateLibOParlCommand;
use OParl\Spec\Commands\UpdateResourcesCommand;
use OParl\Spec\Commands\UpdateSchemaCommand;
use OParl\Spec\Commands\UpdateSpecificationCommand;
use OParl\Spec\Commands\UpdateValidatorCommand;

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
            InitializeCommand::class,
            InitializeSchemaCommand::class,

            UpdateDownloadsCommand::class,
            UpdateLibOParlCommand::class,
            UpdateResourcesCommand::class,
            UpdateSchemaCommand::class,
            UpdateSpecificationCommand::class,
            UpdateValidatorCommand::class,
        ]);
    }
}
