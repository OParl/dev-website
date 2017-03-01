<?php
namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use EFrane\HubSync\RepositoryVersions;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Symfony\Component\Process\Process;

class Job extends \App\Jobs\Job
{
    /**
     * @var string treeish of the synced repository
     */
    protected $treeish = 'master';

    protected $buildMode = 'docker';

    public function __construct($treeish = 'master')
    {
        $this->treeish = $treeish;
        $this->buildMode = env('OPARL_BUILD_MODE', 'native'); # TODO: move this into a configuration variable
    }

    public function getUpdatedHubSync(Filesystem $fs, Log $log)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        if (!$hubSync->update()) {
            $log->error("Git pull failed");
        }

        return $hubSync;
    }

    /**
     * @param $hubSync
     * @param $path
     */
    public function checkoutHubSyncToTreeish(Repository $hubSync, $selectMostRecentVersion = true)
    {
        if ($this->treeish !== $hubSync->getCurrentTreeish() && is_string($this->treeish)) {
            if ($selectMostRecentVersion) {
                $versions = RepositoryVersions::forRepository($hubSync);
                $this->treeish = $versions->getLatestMatchingConstraint($this->treeish);
            }

            $checkoutCmd = "git checkout {$this->treeish}";

            $this->runSynchronousJob($hubSync->getAbsolutePath(), $checkoutCmd);
        }
    }

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

        $process->start();
        $process->wait();

        return $process->getExitCode() == 0;
    }

    public function notifySlack($message)
    {
        if (!config('slack.enabled')) {
            return;
        }

        $args = func_get_args();
        array_shift($args);

        $message = vsprintf($message, $args);

        if (!app()->environment('testing')) {
            \Slack::send($message);
        }
    }

    public function prepareCommand($cmd)
    {
        if ($this->buildMode === 'docker') {
            return sprintf('docker run --rm -v $(pwd):$(pwd) -w $(pwd) oparl/specbuilder:latest %s', $cmd);
        }

        if ($this->buildMode === 'native') {
            return $cmd;
        }

        throw new \LogicException("Unsupported build mode: {$this->buildMode}");
    }
}