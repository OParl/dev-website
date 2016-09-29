<?php

namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class SpecificationCommand extends Command
{
    use DispatchesJobs;

    protected function printCommandInfo($info)
    {
        if (config('queue.default') === 'sync') {
            $this->info("Running {$info}.");
        } else {
            $this->info("Scheduling {$info}.");
        }
    }
}
