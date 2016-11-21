<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 14:50
 */

namespace OParl\Spec\Jobs;


use Symfony\Component\Process\Process;

trait SynchronousProcess
{
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