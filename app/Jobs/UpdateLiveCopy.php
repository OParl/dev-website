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

class UpdateLiveCopy extends Job implements SelfHandling, ShouldQueue
{
  use InteractsWithQueue, SerializesModels;

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(Filesystem $fs, GitHubManager $gh, CacheRepository $cache)
  {
    // remove cached livecopy chapters
    $cache->forget('livecopy:chapters');

    // TODO: Also load images!
    $files = $gh->repo()->contents()->show('OParl', 'specs', '/dokument/master');
    foreach ($files as $file)
    {
      if (ends_with($file['name'], '.md'))
      {
        $data = $gh->repo()->contents()->download('OParl', 'specs', $file['path']);
        $fs->put(LiveCopyRepository::CHAPTER_PATH.$file['name'], $data);
      }
    }
  }
}
