<?php
namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use EFrane\HubSync\RepositoryVersions;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Process\Process;

class Job extends \App\Jobs\Job implements ShouldQueue
{
    const AVAILABLE_BUILD_MODES = ['native', 'docker'];

    /**
     * @var string treeish of the synced repository
     */
    protected $treeish = 'master';

    protected $buildMode = 'docker';

    public function __construct($treeish = 'master')
    {
        $this->treeish = $treeish;

        $this->buildMode = config('oparl.specificationBuildMode');

        if (!in_array($this->buildMode, self::AVAILABLE_BUILD_MODES)) {
            throw new \InvalidArgumentException("Unsupported build mode {$this->buildMode}");
        }
    }

    public function getTreeish()
    {
        return $this->treeish;
    }

    public function getBuildMode()
    {
        return $this->buildMode;
    }

    /**
     * Run a command on the oparl specification repository
     * leaving the repo in a pristine state afterwards
     *
     * @param Repository $repository
     * @param string $cmd unprepared command
     * @param array $args command arguments
     * @return bool command success
     */
    public function runCleanRepositoryCommand(Repository $repository, $cmd, ...$args)
    {
        $result = $this->runRepositoryCommand($repository, $cmd, $args);

        $repository->clean();

        return $result;
    }

    public function runRepositoryCommand(Repository $repository, $cmd, ...$args)
    {
        array_unshift($args, $cmd);
        $prepareCommand = new \ReflectionMethod($this, 'prepareCommand');
        $cmd = $prepareCommand->invokeArgs($this, $args);

        $result = $this->runSynchronousJob($repository->getAbsolutePath(), $cmd);

        return $result;
    }

    public function getUpdatedHubSync(Filesystem $fs, Log $log)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        // failsave checkout to master to ensure we're on an actual branch
        $this->runSynchronousJob($hubSync->getAbsolutePath(), 'git checkout master');

        if (!$hubSync->update()) {
            $log->error("Git pull for OParl/spec failed");
        }

        return $hubSync;
    }

    /**
     * @param Repository $hubSync
     * @param boolean $selectMostRecentVersion
     *
     * @return boolean
     */
    public function checkoutHubSyncToTreeish(Repository $hubSync, $selectMostRecentVersion = true)
    {
        if ($this->treeish !== $hubSync->getCurrentTreeish() && is_string($this->treeish)) {
            if ($selectMostRecentVersion) {
                $versions = RepositoryVersions::forRepository($hubSync);
                $this->treeish = $versions->getLatestMatchingConstraint($this->treeish);
            }

            $checkoutCmd = "git checkout {$this->treeish}";

            if (!$this->runSynchronousJob($hubSync->getAbsolutePath(), $checkoutCmd)) {
                throw new \RuntimeException("Failed to checkout {$this->treeish}");
            }

            return true;
        }

        return false;
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
    public function runSynchronousJob($path, $cmd)
    {
        $process = new Process($cmd, $path);

        $process->start();
        $process->wait();

        return $process->getExitCode() == 0;
    }

    public function notifySlack($message, ...$args)
    {
        if (!config('slack.enabled')) {
            return false;
        }

        if (count($args) > 0) {
            $message = vsprintf($message, $args);
        }

        if (!app()->environment('testing')) {
            \Slack::send($message);
        }

        return true;
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