<?php namespace EFrane\Buildkite;

class Client 
{
  protected $token = '';

  public function __construct()
  {
    $this->token = config('services.buildkite.token');
  }
}
