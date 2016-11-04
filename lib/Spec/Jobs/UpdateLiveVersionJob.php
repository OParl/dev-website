<?php

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;

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
    public function handle(Filesystem $fs)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');
        $hubSync->update();
    }
}
