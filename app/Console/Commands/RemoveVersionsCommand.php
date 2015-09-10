<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\VersionRepository;

class RemoveVersionsCommand extends Command
{
  use DispatchesJobs;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'versions:remove {mode}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Remove either all (except preserved) or extraneous spec versions.';

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
  public function handle(VersionRepository $versionRepository)
  {
    $mode = $this->argument('mode');

    switch ($mode)
    {
      case 'all':
      case 'extraneous':
        $this->line("Removing {$mode} stored versions.");
        $this->dispatch(CleanVersions($mode));
        return true;

      default:
        $this->line("Unknown mode: {$mode}");
        return false;
    }
  }
}
