<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class SpecificationBuildJob extends Job
{
    /**
     * @var array GH Push Hook Payload
     */
    protected $payload = [];

    /**
     * @var string treeish of the synced repository
     */
    protected $treeish = 'master';

    /**
     * SpecificationBuildJob constructor.
     *
     * @param array $payload GH Push Hook Payload
     * @param string $treeish treeish of the synced repository
     */
    public function __construct(array $payload, $treeish = 'master')
    {
        $this->payload = $payload;

        $this->treeish = str_replace([',;\n'], '', $treeish);
    }

    /**
     * @param Filesystem $fs
     */
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

        $fs->move($hubSync->getPath() . '/out/live.html', 'live.html');
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
     * Run a Symfony\Process synchronously
     *
     * Requires a working directory.
     *
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

