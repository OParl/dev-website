<?php
namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Symfony\Component\Process\Process;

class Job extends \App\Jobs\Job
{
    /**
     * @var string treeish of the synced repository
     */
    protected $treeish = 'master';

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
    public function checkoutHubSyncToTreeish(Repository $hubSync)
    {
        if ($this->treeish !== $hubSync->getCurrentTreeish() && is_string($this->treeish)) {
            $checkoutCmd = "git checkout {$this->treeish}";

            $this->runSynchronousJob($hubSync->getAbsolutePath(), $checkoutCmd);
        }
    }

    public function notifySlack($message) {
        $args = func_get_args();
        array_shift($args);

        $message = vsprintf($message, $args);

        if (! app()->environment('testing')) {
            \Slack::send($message);
        }
    }
}