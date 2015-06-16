<?php namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;

class Chapter
{
  protected $filename = '';
  protected $raw = '';
  protected $html = '';
  protected $headlines = [];

  public function __construct(Filesystem $fs, $filename)
  {
    $this->filename = $filename;
    $this->raw = $fs->get($filename);

    $this->parse();
  }

  public function __toString()
  {
    return $this->html;
  }

  protected function parse()
  {
    $parser = new ChapterParser();

    $this->html = $parser->parse($this->raw);
    $this->headlines = $parser->getHeadlines();
  }

  /**
   * @return string
   */
  public function getFilename()
  {
    return $this->filename;
  }

  /**
   * @return string
   */
  public function getRaw()
  {
    return $this->raw;
  }

  /**
   * @return string
   */
  public function getHtml()
  {
    return $this->html;
  }

  /**
   * @return array
   */
  public function getHeadlines()
  {
    return $this->headlines;
  }
}