<?php namespace OParl\Spec\Jobs;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Contracts\Bus\Dispatcher;
use OParl\Spec\Model\SpecificationBuild;

/**
 * Class UpdateAvailableSpecificationVersionsJob
 *
 * Update the available version information from GitHub
 * and import it to the SpecificationBuild table.
 *
 * @package OParl\Spec\Jobs
 */
class UpdateAvailableSpecificationVersionsJob extends SpecificationJob
{
  public function handle(GitHubManager $gh, Dispatcher $dispatcher)
  {
    $commits = $gh->repo()->commits()->all($this->user, $this->repo, []);

    collect($commits)->each(function (array $commit) use ($dispatcher) {
      $hash          = $commit['sha'];
      $commitMessage = $commit['commit']['message'];
      $createdAt     = $commit['commit']['committer']['date'];

      $build = SpecificationBuild::firstOrCreate([
        'hash' => $hash,
        'commitMessage' => $commitMessage,
        'humanVersion' => explode("\n", $commitMessage)[0],
        'createdAt' => $createdAt
      ]);

      
    });
  }
}