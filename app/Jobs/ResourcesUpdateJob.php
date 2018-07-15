<?php

namespace App\Jobs;

use App\Model\Endpoint;
use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
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

        try {
            $endpointsArray = Yaml::parse($fs->get($repo->getPath('endpoints.yml')));

            \Validator::make($endpointsArray, [
                '*.title'       => 'required|string',
                '*.url'         => 'required|url',
                '*.description' => 'string',
            ])->validate();
        } catch (\Exception $e) {
            $this->fail($e);
            $this->notify(
                SpecificationUpdateNotification::resourcesUpdateFailedNotification(
                    $repo->getCurrentHead(),
                    $e->getMessage()
                )
            );

            $log->critical($e->getMessage(), $e->getTrace());

            return;
        }

        $currentEndpoints = collect($endpointsArray);

        $this->invalidateEndpoints($currentEndpoints);

        $currentEndpoints->map(function (array $endpoint) {
            $this->dispatch(new EndpointInfoUpdateJob($endpoint));
        });

        $this->notify(
            SpecificationUpdateNotification::resourcesUpdateSuccesfulNotification(
                $this->treeish,
                $repo->getCurrentHead()
            )
        );
    }

    protected function invalidateEndpoints(Collection $currentEndpoints)
    {
        $currentEndpointUrls = $currentEndpoints->pluck('url')->toArray();

        Endpoint::all()->filter(function (Endpoint $endpoint) use ($currentEndpointUrls) {
            return !in_array($endpoint->url, $currentEndpointUrls);
        })->each(function (Endpoint $endpoint) {
            $endpoint->delete();
        });
    }

    /**
     * @return string slack url
     */
    public function routeNotificationForSlack()
    {
        return config('services.slack.ci.endpoint');
    }
}
