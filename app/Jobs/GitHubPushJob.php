<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 07/10/2016
 * Time: 18:52.
 */

namespace App\Jobs;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;
use OParl\Spec\Jobs\SpecificationLiveVersionBuildJob;
use OParl\Spec\Jobs\SpecificationSchemaBuildJob;
use OParl\Spec\Jobs\ValidatorBuildJob;

/**
 * GitHubPushJob.
 *
 * This is just a meta job which dispatches the actual update
 * job required for a given repository. Hook handling from GH
 * is done this way to ensure as quick a reply to the Hookshoot
 * bot as possible.
 */
class GitHubPushJob extends Job
{
    use DispatchesJobs;

    /**
     * @var string
     */
    protected $repository = '';

    /**
     * @var array json decoded hook payload
     */
    protected $payload = [];

    public function __construct($repository, array $payload)
    {
        $this->repository = $repository;
        $this->payload = $payload;
    }

    public function handle(Log $log)
    {
        switch ($this->repository) {
            case 'spec':
                $this->dispatch(new SpecificationLiveVersionBuildJob(config('oparl.versions.specification.stable')));

                switch ($this->payload['ref']) {
                    case 'refs/heads/master':
                        $this->dispatch(new SpecificationSchemaBuildJob('master'));
                        $this->dispatch(new SpecificationDownloadsBuildJob('master'));
                        break;

                    case 'refs/heads/1.0':
                        $this->dispatch(new SpecificationSchemaBuildJob('~1.0'));
                        $this->dispatch(new SpecificationDownloadsBuildJob('~1.0'));
                        break;

                    default:
                        $log->info("Unknown reference {$this->payload['ref']}, keeping my calm.");
                        break;
                }

                break;

            case 'dev-website':
                // TODO: subtree-split lib/Server to OParl/php-reference-server
                break;

            case 'validator':
                $this->dispatch(new ValidatorBuildJob());
                break;

            case 'resources':
                $this->dispatch(new ResourcesUpdateJob());
                break;

            default:
                $log->error('Cannot process push job for '.$this->repository);
                break;
        }
    }
}
