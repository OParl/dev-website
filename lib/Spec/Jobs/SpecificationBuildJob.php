<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class SpecificationBuildJob extends Job
{
    protected $payload = [];
    protected $treeish = 'master';

    public function __construct($payload, $committish = 'master')
    {
        $this->payload = $payload;

        $this->treeish = str_replace([',;\n'], '', $committish);
    }

    public function handle(Filesystem $fs)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        if (!$hubSync->update()) {
            \Log::error("Git pull failed");
        }

        $path = $hubSync->getAbsolutePath();

        if ($this->treeish !== $hubSync->getCurrentTreeish() && is_string($this->treeish)) {
            $checkoutCmd = "git checkout {$this->treeish}";

            $this->runSynchronousJob($path, $checkoutCmd);
        }

        $dockerCmd = "docker run --rm -v $(pwd):/spec -w /spec oparl/specbuilder:latest make live";

        if (!$this->runSynchronousJob($path, $dockerCmd)) {
            \Log::error("Creating live version html failed");
        }
    }

    /*
    public function updateDownloadables(Filesystem $fs, $path)
    {
        $dockerCmd = "docker run --rm -v $(pwd):/spec -w /spec oparl/specbuilder:latest make clean archives";

        if (!$this->runDockerJob($path, $dockerCmd)) {
            \Log::error("Updating the downloadables failed");
        }
    }
    */

    /**
     * @param $path
     * @param $cmd
     *
     * @return bool
     */
    protected function runSynchronousJob($path, $cmd)
    {
        $process = new Process($cmd, $path);

        \Log::info('Running ' . $process->getCommandLine() . ' in ' . $process->getWorkingDirectory());

        $process->start();
        $process->wait();

        return $process->getExitCode() == 0;
    }
}

