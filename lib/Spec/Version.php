<?php namespace OParl\Spec;

use Carbon\Carbon;

/**
 * Version
 *
 * Information on a Spec Version
 *
 * @package OParl\Spec
 **/
class Version
{
  /**
   * @var string
   **/
  protected $hash = '';

  /**
   * @var string
   **/
  protected $commitMessage = '';

  /**
   * @var Carbon
   **/
  protected $date = null;

  /**
   * @var bool
   **/
  protected $isAvailable = false;

  /**
   * @param $hash string
   * @param $commitMessage string
   * @param $date string
   */
  public function __construct($hash, $commitMessage, $date)
  {
    $this->hash = $hash;
    $this->commitMessage = $commitMessage;
    $this->date = Carbon::createFromFormat(Carbon::ISO8601, $date);

    $this->isAvailable = is_dir(storage_path('app/versions/'.$this->getHash(7)));
  }

  /**
   * @param $length int Hash substring length. Max: 30, Default: 7
   * @return string The version hash, with the requested length
   */
  public function getHash($length = 7)
  {
    return substr($this->hash, 0, $length);
  }

  /**
   * @return string Commit message
   */
  public function getCommitMessage()
  {
    return $this->commitMessage;
  }

  /**
   * @return null|Carbon Creation date
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @return boolean Are the version's files available?
   */
  public function isAvailable()
  {
    return $this->isAvailable;
  }
}