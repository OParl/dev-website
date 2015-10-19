<?php namespace OParl\Spec;

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

      try
      {
          $contents = file_get_contents($fileInfo->getRealPath());
      } catch (\ErrorException $e)
      {
          $contents = '';
      }

      $this->raw = $contents;
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
