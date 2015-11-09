<?php namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\UpdateLiveVersionJob;

class UpdateLiveVersionCommand extends SpecificationCommand
{
    /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'specification:live {--force}';

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
      $this->printCommandInfo('live version update');

      $this->dispatch(new UpdateLiveVersionJob($this->option('force')));
  }
}
