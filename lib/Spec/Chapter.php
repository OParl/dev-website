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
    $this->html = \Parsedown::instance()->parse($this->raw);

    // get headlines
    $text = str_replace(array("\r\n", "\r"), "\n", $this->raw);
    $text = preg_replace('/(.+)\n(=){2,}/', '# $1', $text);
    $text = preg_replace('/(.+)\n(-){2,}/', '## $1', $text);

    $this->headlines = collect(explode("\n", $text))->filter(function ($line) {
      return starts_with(trim($line), '#');
    })->map(function ($headline) {
      return [
        'level' => 0, // FIXME: get level
        'text'  => trim($headline, '# ')
      ];
    });
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