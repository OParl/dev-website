<?php

namespace App\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use OParl\Spec\Jobs\InteractsWithRepositoryTrait;

/**
 * Class ResourcesUpdateJob
 * @package App\Jobs
 */
class ResourcesUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    use InteractsWithRepositoryTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->treeish = 'master'; // resources are always kept on master
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Filesystem $fs, Log $log)
    {
        $repo = new Repository($fs, 'oparl_resources', 'https://github.com/OParl/resources.git');
        $this->getUpdatedHubSync($repo, $log);

        // TODO: validate endpoints.yml

        $fs->copy($repo->getPath('endpoints.yml'), 'live/endpoints.yml');
    }
}
