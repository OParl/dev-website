<?php

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;

class UpdateLiveVersionJob extends SpecificationJob
{
    protected $forceRefresh = false;

    public function __construct($forceRefresh = false)
    {
        $this->forceRefresh = $forceRefresh;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hubSync = new Repository('oparl_spec', 'https://github.com/OParl/spec.git');
        $hubSync->update();
    }
}
