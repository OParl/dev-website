<?php namespace OParl\Spec;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class LiveCopyRepository
{
  const CHAPTER_PATH = 'livecopy/';
  const IMAGE_PATH = 'livecopy/images/';

  /**
   * @var \Illuminate\Support\Collection
   **/
  protected $chapters = null;

  public function __construct(Filesystem $fs, CacheRepository $cache)
  {
    $this->chapters = $cache->rememberForever('livecopy:chapters',
      function () use ($fs) {
        $files = collect($fs->allFiles(static::CHAPTER_PATH));
        return $files->filter(function ($file) {
          return ends_with($file, '.md');
        })->map(function ($chapterFile) use ($fs) {
          return new Chapter($fs, $chapterFile);
        });
      }
    );
  }

  public function __toString()
  {
    return (String)$this->chapters->reduce(function ($carry, $current) {
      return $carry . view('specification.chapter', ['chapter' => $current])->render();
    }, '');
  }

  public function getHeadlines()
  {
    return $this->chapters->map(function (Chapter $chapter) {
      return $chapter->getHeadlines();
    })->reduce(function (Collection $carry, Collection $headlines) {
      return $carry->merge($headlines);
    }, collect())->map(function ($headline) {
      return new Headline($headline['level'], $headline['text']);
    });
  }
}