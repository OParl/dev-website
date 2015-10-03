<?php namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\RequestSpecificationBuildJob;

class RequestSpecificationBuildCommand extends Command
{
  use DispatchesJobs;

  protected $signature = 'specification:build {hash}';
  protected $description = 'Request a build by hash from Buildkite.';

  public function handle()
  {
    if (config('queue.default') === 'sync')
    {
      $this->info('Running specification build request.');
    } else
    {
      $this->info('Scheduling specification build request.');
    }

    $hash = $this->argument('hash');
    $this->dispatch(new RequestSpecificationBuildJob($hash));
  }
}