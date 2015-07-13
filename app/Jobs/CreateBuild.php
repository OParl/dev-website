<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateBuild extends Job implements SelfHandling
{
  protected $hash = '';

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($hash)
  {
    $this->hash = $hash;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $build = new \EFrane\Buildkite\RequestData\CreateBuild("Building requested version {$this->hash}");
    app('buildkite')
      ->builds(config('services.buildkite.user'))
      ->create(config('services.buildkite.project'), $build);
  }
}
