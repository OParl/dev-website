<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;

class ValidatorBuildJob extends Job implements ShouldQueue
{
    use InteractsWithRepositoryTrait;
    use Notifiable;

    public function __construct($treeish = '')
    {
       $this->determineTreeish($treeish, 'oparl.versions.validator.stable');
    }

    public function handle(Filesystem $fs, Log $log)
    {
        $repo = $this->getUpdatedHubSync($this->getRepository($fs), $log);
    }

    /**
     * @param Filesystem $fs
     * @return Repository
     */
    public function getRepository(Filesystem $fs)
    {
        return new Repository($fs, 'oparl_validator', 'https://github.com/OParl/validator.git');
    }
}