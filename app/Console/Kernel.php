<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    Commands\AddUserCommand::class,
    Commands\RemoveUserCommand::class,

    Commands\DeployCommand::class,

    Commands\UpdateLiveCopyCommand::class,

    Commands\ListVersionsCommand::class,
    Commands\UpdateVersionsCommand::class,
    Commands\GetVersionCommand::class,
    Commands\RemoveVersionsCommand::class
  ];

  protected function schedule(Schedule $schedule)
  {
    $schedule->command(Commands\RemoveVersionsCommand::class, ['extraneous'])->daily();
  }
}
