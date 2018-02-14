<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // TODO: Re-add a cleanup command for outdated spec builds
        //$schedule->command(DeleteSpecificationBuildsCommand::class, ['build', 30])->daily();
    }

    protected function commands()
    {
        parent::commands();
        $this->load(__DIR__.'/Commands');
    }
}
