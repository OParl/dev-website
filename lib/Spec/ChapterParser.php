<?php namespace OParl\Spec;

use Parsedown;

class ChapterParser extends Parsedown
{
  protected $headlines = [];

  function blockHeader($line)
  {
    $block = parent::blockHeader($line);

    $this->headlines[] = [
      'level' => intval(substr($block['element']['name'], 1)),
      'text' => $block['element']['text']
    ];

    return $block;
  }

  public function getHeadlines()
  {
    return $this->headlines;
  }
}