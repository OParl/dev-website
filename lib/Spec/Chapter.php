<?php namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class Chapter
 * @package OParl\Spec
 **/
class Chapter
{
  /**
   * @var null|\SplFileInfo
   **/
  protected $fileInfo = null;
  /**
   * @var string
   **/
  protected $raw = '';

  /**
   * @param \SplFileInfo $fileInfo
   */
  public function __construct(\SplFileInfo $fileInfo)
  {
      $this->fileInfo = $fileInfo;
      $this->raw = file_get_contents($fileInfo->getRealPath());
  }

  /**
   * @return string
   **/
  public function getEnriched()
  {
      return view('specification.chapter', [
          'chapter' => $this->raw,
          'filename' => $this->fileInfo->getRealPath()
        ])->render();
  }

  /**
   * @return string
   */
  public function getFilename()
  {
      return $this->fileInfo->getBasename();
  }

  /**
   * @return string
   */
  public function getRaw()
  {
      return $this->raw;
  }

  public function __toString()
  {
    return $this->getRaw();
  }
}
