<?php namespace EFrane\Buildkite\Clients;

use EFrane\Buildkite\BuildkiteException;

class BuildClient extends AbstractClient
{
  protected $organization = '';

  public function __construct($token, $organization)
  {
    parent::__construct($token, $organization);

    return $this->index();
  }

  public function index($organization = null, $project = null)
  {
    if (!is_null($organization))
    {
      $this->id = $this->validateInput($organization);
    }

    if (!is_null($this->id))
    {
      if (!is_null($project))
      {
        $project = $this->validateInput($project);
        return $this->request('GET', 'organizations/' . $this->id . '/projects/' . $project . '/builds');
      }

      return $this->request('GET', 'organizations/' . $this->id . '/builds');
    }

    return $this->request('GET', 'builds');
  }

  public function get($project, $id, $organization = null)
  {
    $organization = $this->validateInput($organization);
    $project = $this->validateInput($project);
    $id = $this->validateInput($id, 'int');

    if (is_null($organization) && !is_null($this->id))
    {
      $organization = $this->id;
    } else throw new BuildkiteException("Unknown organization.");

    return $this->request('GET', 'organizations/' . $organization . '/projects/' . $project . '/builds/' . $id);
  }
}