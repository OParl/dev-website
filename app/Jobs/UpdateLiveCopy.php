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

class UpdateLiveCopy extends SpecificationUpdateJob implements SelfHandling, ShouldQueue
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
    $fs->deleteDirectory(LiveCopyRepository::PATH);

    // load schema
    $schema = $gh->repo($this->user, $this->repo, '/schema', 'HEAD');
    $this->saveFiles($schema, '.json', LiveCopyRepository::getSchemaPath(), $gh, $fs);

    // load examples
    $examples = $gh->repo($this->user, $this->repo, '/examples', 'HEAD');
    $this->saveFiles($examples, '.json', LiveCopyRepository::getExamplesPath(), $gh, $fs);

    // load markdown sources
    $markdown = $gh->repo()->contents()->show($this->user, $this->repo, '/src', 'HEAD');
    $this->saveFiles($markdown, '.md', LiveCopyRepository::getChapterPath(), $gh, $fs);

    // load images (only pngs)
    $images = $gh->repo()->contents()->show($this->user, $this->repo, '/src/images', 'HEAD');
    $this->saveFiles($images, '.png', LiveCopyRepository::getImagesPath(), $gh, $fs);

    // TODO: load that generation script for the last chapter
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
