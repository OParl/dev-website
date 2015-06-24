<?php namespace EFrane\Buildkite\Clients;

final class ClientFactory
{
  protected static $clients = [];

  public static function make($name, $token, $id = null)
  {
    $name = ucfirst(strtolower($name));
    $name = sprintf('EFrane\\Buildkite\\Clients\\%sClient', $name);

    if (class_exists($name))
    {
      $hash = sha1($name . $id);

      if (!in_array($hash, array_keys(static::$clients)))
      {
        static::$clients[$hash] = new $name($token, $id);
      }

      return static::$clients[$hash];
    } else throw new \LogicException("Unknown client `{$name}`.");
  }
}