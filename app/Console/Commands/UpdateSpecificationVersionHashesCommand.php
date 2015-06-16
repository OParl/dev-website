<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSpecificationVersionHashes;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateSpecificationVersionHashesCommand extends Command
{
  use DispatchesJobs;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'maintenance:versions';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update specification version information from GitHub.';

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
    $this->info("Scheduling update job.");
    $this->dispatch(new UpdateSpecificationVersionHashes());
  }
}
