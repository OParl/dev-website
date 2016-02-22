<?php

namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\UpdateAvailableSpecificationVersionsJob;

class UpdateSpecificationBuildDataFromGitHubCommand extends SpecificationCommand
{
    protected $name = 'specification:update';
    protected $description = 'Update build information from GitHub.';

    public function handle()
    {
        $this->printCommandInfo('specification build data update');

        $this->dispatch(new UpdateAvailableSpecificationVersionsJob());
    }
}
