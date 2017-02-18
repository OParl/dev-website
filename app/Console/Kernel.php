<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use OParl\Spec\Commands\DeleteSpecificationBuildsCommand;

class Kernel extends ConsoleKernel
{
    /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    Commands\SetupCommand::class,
  ];

    protected function schedule(Schedule $schedule)
    {
        // TODO: Re-add a cleanup command for outdated spec builds
        //$schedule->command(DeleteSpecificationBuildsCommand::class, ['build', 30])->daily();
    }
}
