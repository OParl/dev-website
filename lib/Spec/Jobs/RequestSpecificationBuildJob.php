<?php

namespace OParl\Spec\Jobs;

use EFrane\Buildkite\Buildkite;
use EFrane\Buildkite\RequestData\CreateBuild;
use OParl\Spec\BuildRepository;

class RequestSpecificationBuildJob extends SpecificationJob
{
    protected $hash = '';

  /**
   * RequestSpecificationBuildJob constructor.
   *
   * @param string $hash
   */
  public function __construct($hash)
  {
      $this->hash = $hash;
  }

  /**
   *
   */
  public function handle(Buildkite $bk, BuildRepository $repo)
  {
      if (env('debug')) {
          \Log::info("Would request specification build for hash {$this->hash}");

          return true;
      }

      $project = config('services.buildkite.project');
      if ($bk->builds()->project($project)->hasActiveBuild()) {
          $this->release(60);
          \Log::debug('Released build request onto queue due to an active build.');
      }

      $build = $repo->getWithHash($this->hash);
      if ($build->isAvailable) {
          \Log::debug('Build is already available, aborting.');

          return true;
      }

      $request = new CreateBuild("Building requested version {$this->hash}", $this->hash);
      $bk->builds()->project($project)->create($request);
  }
}
