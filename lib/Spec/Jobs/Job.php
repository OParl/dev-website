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

    /**
     * Run a command on the oparl specification repository
     * leaving the repo in a pristine state afterwards
     *
     * @param Repository $repository
     * @param string $cmd unprepared command
     * @return bool command success
     */
    public function runCleanRepositoryCommand(Repository $repository, $cmd, ...$args)
    {
        array_unshift($args, $cmd);
        $prepareCommand = new \ReflectionMethod($this, 'prepareCommand');
        $cmd = $prepareCommand->invokeArgs($this, $args);

        $result = $this->runSynchronousJob($repository->getAbsolutePath(), $cmd);
        $repository->clean();

        return $result;
    }

    public function getUpdatedHubSync(Filesystem $fs, Log $log)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        // failsave checkout to master to ensure we're on an actual branch
        $this->runSynchronousJob($hubSync->getAbsolutePath(), 'git checkout master');

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

    public function prepareCommand($cmd, ...$args)
    {
        if (count($args) > 0) {
            $cmd = vsprintf($cmd, $args);
        }

        if ($this->buildMode === 'native') {
            return $cmd;
        }

        if ($this->buildMode === 'docker') {
            return sprintf('docker run --rm -v $(pwd):$(pwd) -w $(pwd) oparl/specbuilder:latest %s', $cmd);
        }

        throw new \LogicException("Unsupported build mode: {$this->buildMode}");
    }
}