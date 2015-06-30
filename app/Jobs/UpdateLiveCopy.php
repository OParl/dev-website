<?php

namespace App\Jobs;

use GrahamCampbell\GitHub\GitHubManager;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use OParl\Spec\LiveCopyRepository;

use PharData;

class UpdateLiveCopy extends SpecificationUpdateJob implements SelfHandling, ShouldQueue
{
  use InteractsWithQueue, SerializesModels;

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(Filesystem $fs, CacheRepository $cache)
  {
    // remove cached livecopy chapters
    $cache->forget('livecopy:chapters');
    $cache->forget('livecopy:html');

    $fs->deleteDirectory(LiveCopyRepository::PATH);

    // get tarball
    $tarballUrl = sprintf('https://github.com/%s/%s/archive/master.tar.gz', $this->user, $this->repo);

    chdir(storage_path().'/app');
    exec('wget '.$tarballUrl);
    exec('tar -xvzf master.tar.gz', $output, $ret);

    unlink('master.tar.gz');
  }

  protected function saveFiles(array $files, $extension, $path, GitHubManager $gh, Filesystem $fs)
  {
    foreach ($files as $file)
    {
      if (ends_with($file['name'], $extension))
      {
        $data = $gh->repo()->contents()->download($this->user, $this->repo, $file['path']);
        $fs->put($path . $file['name'], $data);
      }
    }
  }
}
