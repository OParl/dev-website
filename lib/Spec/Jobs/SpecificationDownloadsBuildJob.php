<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 14:48
 */

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;

class SpecificationDownloadsBuildJob
{
    use SynchronousProcess;

    public function handle(Filesystem $fs)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        if (!$hubSync->update()) {
            \Log::error("Git pull failed");
        }

        $dockerCmd = "docker run --rm -v $(pwd):/spec -w /spec oparl/specbuilder:latest make clean archives";

        if (!$this->runSynchronousJob($hubSync->getAbsolutePath(), $dockerCmd)) {
            \Log::error("Updating the downloadables failed");
        }

        // TODO: move archives and downloadables to persistent locations
        
    }
}