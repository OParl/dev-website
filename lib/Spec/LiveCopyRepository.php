<?php namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class LiveCopyRepository
{
  const CHAPTER_PATH = 'livecopy/';

  /**
   * @var \Illuminate\Support\Collection
   **/
  protected $chapters = null;

  public function __construct(Filesystem $fs)
  {
    $files = collect($fs->allFiles(static::CHAPTER_PATH));
    $this->chapters = $files->map(function ($chapterFile) use ($fs) {
      return new Chapter($fs, $chapterFile);
    });
  }

  public function __toString()
  {
    return $this->chapters->reduce(function ($carry, $current) {
      return $carry . view('specification.chapter', ['chapter' => $current])->render();
    }, '');
  }

  public function getHeadlines()
  {
    return $this->chapters->map(function (Chapter $chapter) {
      return $chapter->getHeadlines();
    })->reduce(function (Collection $carry, Collection $headlines) {
      return $carry->merge($headlines);
    }, collect());
  }
}