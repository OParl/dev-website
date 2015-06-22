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
    $cache->forget('livecopy:html');

    // remove livecopy assets
    $fs->deleteDirectory(LiveCopyRepository::CHAPTER_PATH);

    $files = $gh->repo()->contents()->show('OParl', 'specs', '/dokument/master', 'HEAD');
    foreach ($files as $file)
    {
      if (ends_with($file['name'], '.md'))
      {
        // load a chapter file
        $data = $gh->repo()->contents()->download('OParl', 'specs', $file['path']);
        $fs->put(LiveCopyRepository::CHAPTER_PATH.$file['name'], $data);
      }
    }

    // load images
    $images = $gh->repo()->contents()->show('OParl', 'specs', '/dokument/master/images', 'HEAD');
    foreach ($images as $image)
    {
      // only load pngs
      if (ends_with($image['name'], '.png'))
      {
        $data = $gh->repo()->contents()->download('OParl', 'specs', $image['path']);
        $fs->put(LiveCopyRepository::IMAGE_PATH.$image['name'], $data);
      }
    }
  }
}
