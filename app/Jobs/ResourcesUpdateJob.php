<?php

namespace App\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use OParl\Spec\Jobs\InteractsWithRepositoryTrait;

/**
 * Class ResourcesUpdateJob.
 */
class ResourcesUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    use InteractsWithRepositoryTrait;
    use Notifiable;

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
        // $this->notify(SpecificationUpdateNotification::resourcesUpdateFailedNotification($this->treeish));

        $fs->delete('live/endpoints.yml');
        $fs->copy($repo->getPath('endpoints.yml'), 'live/endpoints.yml');

        $this->notify(SpecificationUpdateNotification::resourcesUpdateSuccesfulNotification($repo->getCurrentHead()));
    }

    /**
     * @return string slack url
     */
    public function routeNotificationForSlack()
    {
        return config('services.slack.ci.endpoint');
    }
}
