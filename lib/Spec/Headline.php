<?php namespace OParl\Spec;

class Headline 
{
  protected $level  = 0;
  protected $text   = '';
  protected $anchor = '';

  public function __construct($level, $text)
  {
    $this->level = $level;
    $this->text = $text;

    $this->parseAnchor();
  }

  protected function parseAnchor()
  {
    if (preg_match('/\{(#.+)\}/', $this->text, $match))
    {
      $this->text = str_replace($match[0], '', $this->text);
      $this->anchor = $match[1];
    } else
    {
      $this->anchor = '';
    }
  }

  /**
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * @return int
   */
  public function getLevel()
  {
    return $this->level;
  }

  /**
   * @return string
   */
  public function getAnchor()
  {
    return $this->anchor;
  }
}