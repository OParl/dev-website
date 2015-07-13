<?php

namespace App\Console\Commands;

use App\Jobs\UpdateLiveCopy;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateLiveCopyCommand extends Command
{
  use DispatchesJobs;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'maintenance:livecopy {--force}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update the live copy.';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $this->info('Scheduling live copy update.');
    $this->dispatch(new UpdateLiveCopy($this->option('force')));
  }
}
