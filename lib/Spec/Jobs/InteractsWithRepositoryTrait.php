<?php

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use EFrane\HubSync\RepositoryVersions;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Symfony\Component\Process\Process;

trait InteractsWithRepositoryTrait
{
    /**
     * @var string treeish of the synced repository
     */
    protected $treeish = '';

    /**
     * @param string $treeish
     * @param string $default config option for the default constraint
     */
    public function determineTreeish($treeish, $default)
    {
        $this->treeish = $treeish;

        if (!is_string($treeish) || strlen($treeish) === 0) {
            $this->treeish = config($default);
        }
    }

    /**
     * @return string
     */
    public function getTreeish()
    {
        return $this->treeish;
    }

    /**
     * Run a command on a repository, leaving the repo in a pristine state afterwards.
     *
     * @param Repository $repository
     * @param string $cmd unprepared command
     * @param array $args command arguments
     *
     * @return bool command success
     */
    public function runCleanRepositoryCommand(Repository $repository, $cmd, ...$args)
    {
        $result = $this->runRepositoryCommand($repository, $cmd, $args);

        $repository->clean();

        return $result;
    }

    /**
     * @param Repository $repository
     * @param $cmd
     * @param array ...$args
     *
     * @return bool
     */
    public function runRepositoryCommand(Repository $repository, $cmd, ...$args)
    {
        array_unshift($args, $cmd);

        $prepareCommand = new \ReflectionMethod($this, 'prepareCommand');
        $cmd = $prepareCommand->invokeArgs($this, $args);

        $result = $this->runSynchronousCommand($repository->getAbsolutePath(), $cmd);

        return $result;
    }

    /**
     * Run a Symfony\Process synchronously.
     *
     * Requires a working directory.
     *
     * @param $path
     * @param $cmd
     *
     * @return bool
     */
    public function runSynchronousCommand($path, $cmd)
    {
        $process = new Process($cmd, $path);

        $process->start();
        $process->wait();

        return $process->getExitCode() === 0;
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     * @return Repository
     */
    public function getUpdatedHubSync(Filesystem $fs, Log $log)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        // failsave checkout to master to ensure we're on an actual branch
        $this->runSynchronousCommand($hubSync->getAbsolutePath(), 'git checkout master');

        if (!$hubSync->update()) {
            $log->error('Git pull for OParl/spec failed');
        }

        return $hubSync;
    }

    /**
     * @param Repository $hubSync
     * @param bool $selectMostRecentVersion
     *
     * @return bool
     */
    public function checkoutHubSyncToTreeish(Repository $hubSync, $selectMostRecentVersion = true)
    {
        if ($this->treeish !== $hubSync->getCurrentTreeish() && is_string($this->treeish)) {
            if ($selectMostRecentVersion) {
                $versions = RepositoryVersions::forRepository($hubSync);
                $this->treeish = $versions->getLatestMatchingConstraint($this->treeish);
            }

            $checkoutCmd = "git checkout {$this->treeish}";

            if (!$this->runSynchronousCommand($hubSync->getAbsolutePath(), $checkoutCmd)) {
                throw new \RuntimeException("Failed to checkout {$this->treeish}");
            }

            return true;
        }

        return false;
    }

    /**
     * @param $cmd
     * @param array ...$args
     * @return string
     */
    public function prepareCommand($cmd, ...$args)
    {
        if (count($args) > 0) {
            $cmd = vsprintf($cmd, $args);
        }

        return $cmd;
    }
}