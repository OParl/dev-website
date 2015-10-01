<?php namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\UpdateAvailableSpecificationVersionsJob;

class UpdateSpecificationBuildDataFromGitHubCommand extends Command
{
  use DispatchesJobs;

  protected $name = 'specification:update';
  protected $description = 'Update build information from GitHub.';

  public function handle()
  {
    if (config('queue.driver') === 'sync')
    {
      $this->info('Running specification build data update.');
    } else
    {
      $this->info('Scheduling specification build data update.');
    }

    $this->dispatch(new UpdateAvailableSpecificationVersionsJob());
  }
}