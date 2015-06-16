<?php namespace OParl\Spec;

use ArrayAccess;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class LiveCopyRepository implements ArrayAccess
{
  const CHAPTER_PATH = 'livecopy/';

  /**
   * @var \Illuminate\Support\Collection
   **/
  protected $chapters = null;

  public function __construct(Filesystem $fs)
  {
    $files = collect($fs->allFiles(static::CHAPTER_PATH));
    $this->chapters = $files->map(function ($chapterFile) use ($fs) {
      return new Chapter($fs, $chapterFile);
    });
  }

  public function __toString()
  {
    return $this->chapters->reduce(function ($carry, $current) {
      return $carry . $current;
    }, '');
  }

  public function getHeadlines()
  {
    return $this->chapters->map(function (Chapter $chapter) {
      return $chapter->getHeadlines();
    })->reduce(function (Collection $carry, Collection $headlines) {
      return $carry->merge($headlines);
    }, collect());
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   * @param mixed $offset <p>
   * An offset to check for.
   * </p>
   * @return boolean true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
    return isset($this->chapters[$offset]);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   * @param mixed $offset <p>
   * The offset to retrieve.
   * </p>
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
    return $this->chapters[$offset];
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   * @param mixed $offset <p>
   * The offset to assign the value to.
   * </p>
   * @param mixed $value <p>
   * The value to set.
   * </p>
   * @return void
   */
  public function offsetSet($offset, $value)
  {
    $this->chapters[$offset] = $value;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   * @param mixed $offset <p>
   * The offset to unset.
   * </p>
   * @return void
   */
  public function offsetUnset($offset)
  {
    unset($this->chapters[$offset]);
  }
}