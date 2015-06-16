<?php namespace OParl\Spec;

use Carbon\Carbon;

class Version 
{
  protected $hash = '';
  protected $commitMessage = '';
  protected $date = null;
  protected $isAvailable = false;

  public function __construct($hash, $commitMessage, $date)
  {
    $this->hash = $hash;
    $this->commitMessage = $commitMessage;
    $this->date = Carbon::createFromFormat(Carbon::ISO8601, $date);

    // TODO: build status
  }

  /**
   * @return string
   */
  public function getHash($length = 30)
  {
    return substr($this->hash, 0, $length);
  }

  /**
   * @return string
   */
  public function getCommitMessage()
  {
    return $this->commitMessage;
  }

  /**
   * @return null|static
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @return boolean
   */
  public function isAvailable()
  {
    return $this->isAvailable;
  }
}