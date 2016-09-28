<?php

namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class SpecServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // make sure the container transparently handles the repositories as singletons
        $this->app->bind(LiveVersionRepository::class, LiveVersionRepository::class, true);
        $this->app->bind(BuildRepository::class, BuildRepository::class, true);
        $this->app->bind(SchemaRepository::class, SchemaRepository::class, true);

        $this->app->bind(LiveVersionUpdater::class, function () {
            $fs = app(Filesystem::class);

            $config = config('services.repositories.spec');
            $gitURL = sprintf('https://github.com/%s/%s.git', $config['user'], $config['repository']);

            return new LiveVersionUpdater($fs, LiveVersionRepository::PATH, $gitURL);
        });

        $this->app->bind(LiveVersionBuilder::class, function () {
            $fs = app(Filesystem::class);

            return new LiveVersionBuilder($fs, LiveVersionRepository::getLiveVersionPath());
        });

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

        $this->app->singleton(
            'oparl.specification.commands.update_live_copy',
            Commands\UpdateLiveVersionCommand::class
        );

        $this->commands([
            'oparl.specification.commands.delete_builds',
            'oparl.specification.commands.update_builds_gh',
            'oparl.specification.commands.request_build_bk',
            'oparl.specification.commands.list_builds',
            'oparl.specification.commands.update_live_copy',
        ]);
    }
}
