<?php

namespace OParl\Spec\Jobs;

use OParl\Spec\LiveVersionUpdater;

class UpdateLiveVersionJob extends SpecificationJob
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
  public function handle(LiveVersionUpdater $updater)
  {
      $updater->updateRepository($this->forceRefresh);
  }
}
