<?php
namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class Job extends \App\Jobs\Job
{
    public function getUpdatedHubSync(Filesystem $fs, Log $log)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        if (!$hubSync->update()) {
            $log->error("Git pull failed");
        }

        return $hubSync;
    }
}