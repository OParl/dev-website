<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 13/10/2016
 * Time: 10:50
 */

namespace App\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class LiveVersionUpdateJob extends Job
{
    /**
     * @var array
     */
    protected $payload = [];

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(Filesystem $fs)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');
        $hubSync->update();

        // run the make process in docker
        $makeCommand = 'docker run --rm -v %s:/spec -w /spec oparl/specbuilder:latest make clean all liveversion';
        $make = new Process(sprintf($makeCommand, $hubSync->getAbsolutePath()));

    }
}