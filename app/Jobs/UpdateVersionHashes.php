<?php namespace App\Jobs;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use OParl\Spec\VersionRepository;

class UpdateVersionHashes extends SpecificationUpdateJob implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Filesystem $fs, GitHubManager $gh)
    {
      $commits = $gh->repo()->commits()->all($this->user, $this->repo, []);
      $fs->put(VersionRepository::REPOSITORY_FILE, json_encode($commits));
    }
}
