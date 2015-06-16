<?php namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;

class Chapter
{
  protected $filename = '';
  protected $raw = '';

  public function __construct(Filesystem $fs, $filename)
  {
    $this->filename = $filename;
    $this->raw = $fs->get($filename);
  }

  public function __toString()
  {
    return \Parsedown::instance()->parse($this->raw);
  }
}