<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSpecificationVersionHashes extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Filesystem $fs)
    {
      $gh = app('github');
      $commits = $gh->repo()->commits()->all('OParl', 'specs', []);
      $fs->put('specs_versions.json', json_encode($commits));
    }
}
