<?php

namespace App\Console\Commands;

use App\Jobs\UpdateVersionHashes;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateVersionsCommand extends Command
{
  use DispatchesJobs;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'versions:update';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update specification version information from GitHub.';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $this->info('Scheduling version hashes update.');
    $this->dispatch(new UpdateVersionHashes());
  }
}
