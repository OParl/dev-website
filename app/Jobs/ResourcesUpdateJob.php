<?php

namespace App\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use OParl\Spec\Jobs\InteractsWithRepositoryTrait;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ResourcesUpdateJob.
 */
class ResourcesUpdateJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable;
    use InteractsWithRepositoryTrait;
    use Notifiable;
    use DispatchesJobs;

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
     * @param Filesystem $fs
     * @param Log        $log
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(Filesystem $fs, Log $log)
    {
        $repo = new Repository($fs, 'oparl_resources', 'https://github.com/OParl/resources.git');
        $this->getUpdatedHubSync($repo, $log);

        // TODO: validate endpoints.yml
        // $this->notify(SpecificationUpdateNotification::resourcesUpdateFailedNotification($this->treeish));

        collect(Yaml::parse($fs->get($repo->getPath('endpoints.yml'))))->map(function (array $endpoint) {
            $this->dispatch(new EndpointInfoUpdateJob($endpoint));
        });

        //$this->notify(SpecificationUpdateNotification::resourcesUpdateSuccesfulNotification($repo->getCurrentHead()));
    }

    /**
     * @return string slack url
     */
    public function routeNotificationForSlack()
    {
        return config('services.slack.ci.endpoint');
    }
}
