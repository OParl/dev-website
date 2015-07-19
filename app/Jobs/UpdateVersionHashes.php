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
      VersionRepository::update($fs, $gh, $this->user, $this->repo);
    }
}
