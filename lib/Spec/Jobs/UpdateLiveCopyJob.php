<?php namespace OParl\Spec\Jobs;

use GrahamCampbell\GitHub\GitHubManager;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use OParl\Spec\LiveCopyRepository;

use PharData;

class UpdateLiveCopyJob extends SpecificationJob
{
  use InteractsWithQueue, SerializesModels;

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
