<?php namespace EFrane\Buildkite\Clients;

use EFrane\Buildkite\BuildkiteException;

class BuildClient extends AbstractClient
{
  protected $organization = '';

  public function __construct($token, $organization)
  {
    if (is_null($organization)) throw new BuildkiteException("\$organization must be valid.");
    parent::__construct($token, $organization);
  }

  public function index($organization, $project)
  {
    $organization = $this->validateInput($organization);
    $project = $this->validateInput($project);

    return $this->request('GET', 'organizations/' . $organization . '/projects/' . $project . '/builds');
  }
}