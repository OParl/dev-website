<?php

namespace EFrane\Buildkite\RequestData;

class AbstractRequestData implements \ArrayAccess
{
    /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   *
   * @param mixed $offset <p>
   * An offset to check for.
   * </p>
   *
   * @return bool true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
      return property_exists($this, $offset) && !is_null($this->{$offset});
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset <p>
   * The offset to retrieve.
   * </p>
   *
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
      return $this->{$offset};
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   *
   * @param mixed $offset <p>
   * The offset to assign the value to.
   * </p>
   * @param mixed $value <p>
   * The value to set.
   * </p>
   *
   * @return void
   */
  public function offsetSet($offset, $value)
  {
      $this->{$offset} = $value;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset <p>
   * The offset to unset.
   * </p>
   *
   * @return void
   */
  public function offsetUnset($offset)
  {
      $this->{$offset} = null;
  }

    public function toArray()
    {
        return collect($this)->filter(function ($item) {
      return !is_null($item);
    })->toArray();
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
