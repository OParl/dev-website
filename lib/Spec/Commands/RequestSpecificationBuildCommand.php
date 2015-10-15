<?php namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\RequestSpecificationBuildJob;

class RequestSpecificationBuildCommand extends SpecificationCommand
{
    protected $signature = 'specification:build {hash}';
    protected $description = 'Request a build by hash from Buildkite.';

    public function handle()
    {
        $this->printCommandInfo('specification build request');

        $hash = $this->argument('hash');
        $this->dispatch(new RequestSpecificationBuildJob($hash));
    }
}
