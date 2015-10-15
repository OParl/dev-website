<?php namespace OParl\Spec\Jobs;

use OParl\Spec\LiveCopyRepository;

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
  public function handle(LiveCopyRepository $repository)
  {
      $repository->refresh($this->user, $this->repo, $this->forceRefresh);
  }
}
