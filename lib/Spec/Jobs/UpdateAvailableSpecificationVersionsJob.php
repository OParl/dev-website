<?php

namespace OParl\Spec\Jobs;

use Carbon\Carbon;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Contracts\Bus\Dispatcher;
use OParl\Spec\Model\SpecificationBuild;

/**
 * Class UpdateAvailableSpecificationVersionsJob.
 *
 * Update the available version information from GitHub
 * and import it to the SpecificationBuild table.
 */
class UpdateAvailableSpecificationVersionsJob extends SpecificationJob
{
    public function handle(GitHubManager $gh, Dispatcher $dispatcher)
    {
        $commits = $gh->repo()->commits()->all($this->user, $this->repo, []);

        collect($commits)->each(function (array $commit) use ($dispatcher) {
            $hash = $commit['sha'];
            $commitMessage = $commit['commit']['message'];
            $createdAt = new Carbon($commit['commit']['committer']['date']);
            $humanVersion = explode("\n", $commitMessage)[0];

            SpecificationBuild::firstOrCreate([
        'commit_message' => $commitMessage,
        'human_version'  => $humanVersion,
        'created_at'     => $createdAt,
        'hash'           => $hash,
      ]);
        });
    }
}
