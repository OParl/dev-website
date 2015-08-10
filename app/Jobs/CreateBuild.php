<?php namespace App\Jobs;

use App\Model\ScheduledBuild;
use Illuminate\Contracts\Bus\SelfHandling;

use EFrane\Buildkite\RequestData\CreateBuild as CreateBuildRequest;

/**
 * Create Build Job
 *
 * This job is used to create spec builds on Buildkite
 * on-demand. The code flow for on-demand builds as far as
 * this job is concerned is:
 *
 * 1) User requests unavailable version
 * 2) This job is being scheduled
 * 3) This job calls the Buildkite API to execute the build
 * 4) This job is done -> Processing continues at HooksController@addVersion
 *
 * @package App\Jobs
 **/
class CreateBuild extends Job implements SelfHandling
{
  /**
   * @var string The requested version
   **/
  protected $hash   = '';

  /**
   * @var string The requested format (required for info mail)
   **/
  protected $format = '';

  /**
   * Create a new Build Job instance
   *
   * @param string $hash The requested version
   * @param string $format The requested format (required for info mail)
   */
  public function __construct($hash, $format)
  {
    $this->hash = $hash;
    $this->format = $format;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if (!env('debug'))
    {
      // create the build
      $build = new CreateBuildRequest(
        "Building requested version {$this->hash}",
        $this->hash
      );

      app('buildkite')
        ->builds(config('services.buildkite.project'))
        ->create($build);
    }
  }
}
