<?php namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\UpdateLiveCopyJob;

class UpdateLiveCopyCommand extends SpecificationCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'specification:livecopy {--force}';

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
    $this->printCommandInfo('live copy update');

    $this->dispatch(new UpdateLiveCopyJob($this->option('force')));
  }
}
