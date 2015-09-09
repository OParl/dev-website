<?php

namespace App\Console\Commands;

use App\Jobs\CreateBuild;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GetVersionCommand extends Command
{
  use DispatchesJobs;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'versions:get {hash}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Dispatches a job to Buildkite for compiling a specific version.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $hash = $this->argument('hash');

    $this->info("Scheduling build create job for {$hash}");

    $this->dispatch(new CreateBuild($hash, '[Artisan]'));
  }
}
