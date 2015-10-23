<?php namespace OParl\Spec\Jobs;

use OParl\Spec\LiveCopyLoader;

class UpdateLiveCopyJob extends SpecificationJob
{
    protected $forceRefresh = false;

    public function __construct($forceRefresh = false)
    {
        $this->forceRefresh = $forceRefresh;
    }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(LiveCopyLoader $loader)
  {
      $loader->updateRepository($this->forceRefresh);
  }
}
