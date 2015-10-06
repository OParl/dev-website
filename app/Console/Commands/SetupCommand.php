<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
  protected $signature = 'setup';
  protected $description = 'Runs all commands necessary for initial setup of the application.';

  public function handle()
  {
    $this->info('Setting up the application...');

    $this->call('down');

    $this->call('migrate');

    $this->call('livecopy:update --force');
    $this->call('specification:update');

    $this->call('up');
  }
}