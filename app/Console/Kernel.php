<?php

namespace App\Console;

use App\Jobs\ResourcesUpdateJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(ResourcesUpdateJob::class)->saturdays();
    }

    protected function commands()
    {
        parent::commands();
        $this->load(__DIR__.'/Commands');
    }
}
