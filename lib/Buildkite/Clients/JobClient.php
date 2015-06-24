<?php namespace EFrane\Buildkite\Clients;

class JobClient extends AbstractClient
{
  protected $organization = '';

  public function __construct($token, $organization)
  {
    if (is_null($organization)) throw new BuildkiteException("\$organization must be valid.");
    parent::__construct($token, $organization);
  }
}