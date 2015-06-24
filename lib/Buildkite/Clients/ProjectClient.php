<?php namespace EFrane\Buildkite\Clients;

use EFrane\Buildkite\BuildkiteException;

class ProjectClient extends AbstractClient
{
  protected $organization = '';

  public function __construct($token, $organization)
  {
    if (is_null($organization)) throw new BuildkiteException("\$organization must be valid.");
    parent::__construct($token, $organization);

    if (is_string($organization)) return $this->index($organization);
  }

  protected function setOrganization($organization)
  {
    if (is_null($organization))
    {
      $organization = $this->id;
    } else {
      $organization = $this->validateInput($organization);
    }

    return $organization;
  }

  public function index($organization = null)
  {
    $organization = $this->setOrganization($organization);
    return $this->request('GET', 'organizations/'.$organization.'/projects');
  }

  public function get($project, $organization = null)
  {
    $organization = $this->setOrganization($organization);
    $project = $this->validateInput($project);

    return $this->request('GET', 'organizations/' . $organization . '/projects/' . $project);
  }
}