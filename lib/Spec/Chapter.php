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

  public function getEnriched()
  {
    return view('specification.chapter', [
      'chapter' => $this->raw,
      'filename' => $this->filename
    ])->render();
  }

  /**
   * @return string
   */
  public function getFilename()
  {
    return basename($this->filename);
  }

  /**
   * @return string
   */
  public function getRaw()
  {
    return $this->raw;
  }
}