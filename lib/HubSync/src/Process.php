<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 31/12/2016
 * Time: 09:57
 */

namespace EFrane\HubSync;

use Symfony\Component\Process\Process as SymfonyProcess;

trait Process
{
    public function synchronousProcess($cmd, $timeout = 60)
    {
        $process = new SymfonyProcess($cmd);

        $process->setTimeout($timeout);
        $process->start();
        $process->wait();

        return trim($process->getOutput());
    }
}