<?php namespace App\Jobs;

use App\Model\Environment;
use App\Model\ScheduledBuild;
use Illuminate\Contracts\Bus\SelfHandling;
use EFrane\Buildkite\RequestData\CreateBuild as CreateBuildRequest;

/**
 * Create Build Job
 *
 * This job is used to create spec builds on Buildkite
 * on-demand.
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
   * @var string Meta information to be added to the build request message
   **/
  protected $buildMeta = '';

  /**
   * Create a new Build Job instance
   *
   * @param string $hash The requested version
   */
  public function __construct($hash, $meta = '')
  {
      $this->hash = $hash;
      $this->buildMeta = ' '.$meta;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
      if (!env('debug') && !Environment::get('versions')['updateInProgress']) {
          // create the build
      $build = new CreateBuildRequest(
        "Building requested version {$this->hash}{$this->buildMeta}",
        $this->hash
      );

          Environment::set('versions', [
        'updateInProgress' => false,
        'hash' => $this->hash
      ]);

          app('buildkite')
        ->builds(config('services.buildkite.project'))
        ->create($build);
      }
  }
}
