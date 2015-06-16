<?php

namespace App\Jobs;

use App\Jobs\Job;
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
  public function handle(Filesystem $fs)
  {
    $gh = app('github');
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
