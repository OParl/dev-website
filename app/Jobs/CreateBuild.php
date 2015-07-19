<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\ScheduledBuild;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Filesystem\Filesystem;

class CreateBuild extends Job implements SelfHandling
{
  protected $hash   = '';
  protected $email  = '';
  protected $format = '';

  /**
   * Create a new Build Job instance
   *
   * @param string $hash
   * @param string $email
   * @param string $format
   */
  public function __construct($hash, $email, $format)
  {
    $this->hash = $hash;
    $this->email = $email;
    $this->format = $format;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(Filesystem $fs)
  {
    // create the build
    $build = new \EFrane\Buildkite\RequestData\CreateBuild(
      "Building requested version {$this->hash}",
      $this->hash
    );

    app('buildkite')
      ->builds(config('services.buildkite.project'))
      ->create($build);

    // store the information for the add_version hoo<k
    ScheduledBuild::create([
      'version' => $this->hash,
      'email'   => $this->email,
      'format'  => $this->format
    ]);
  }
}
