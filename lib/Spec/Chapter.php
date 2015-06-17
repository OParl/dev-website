<?php namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;
use Pandoc\Pandoc;

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
    $this->parseMarkdown();
    $this->parseHeadlines();
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

  protected function parseHeadlines()
  {
    // normalize line breaks
    $text = str_replace(array("\r\n", "\r"), "\n", $this->raw);

    // convert underline-style headlines to hash-style headlines
    $text = preg_replace('/(.+)\n(=){2,}/', '# $1', $text);
    $text = preg_replace('/(.+)\n(-){2,}/', '## $1', $text);

    // check for headlines per line
    $this->headlines = collect(explode("\n", $text))->filter(function ($line) {
      return starts_with(trim($line), '#');
    })->map(function ($headline) {
      $level = 1;

      while (strlen($headline) >= $level && $headline[$level] === '#')
        $level++;

      return [
        'level' => $level,
        'text' => trim($headline, '# ')
      ];
    });
  }

  protected function parseMarkdown()
  {
    // transform with pandoc
    $pandoc = new Pandoc();

    $this->html = $pandoc->runWith($this->raw, [
      'from' => 'markdown',
      'to' => 'html5',
    ]);

    // fix image urls
    $this->html = preg_replace('/"(.?)(images\/.+\.png)"/', '"$1/specification/$2"', $this->html);

    // fix image tags
    $this->html = str_replace('<img ', '<img class="img-responsive"', $this->html);
  }
}