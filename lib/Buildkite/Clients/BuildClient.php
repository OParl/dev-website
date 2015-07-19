<?php namespace EFrane\Buildkite\Clients;

use EFrane\Buildkite\BuildkiteException;
use EFrane\Buildkite\RequestData\CreateBuild;

class BuildClient extends AbstractClient
{
  public function __construct($token, $organization, $project = null)
  {
    if (is_null($organization)) throw new BuildkiteException("\$organization must be valid.");
    parent::__construct($token, $organization);
  }

  public function index()
  {
    if (!is_null($this->organization))
    {
      if (!is_null($this->project))
      {
        $this->project = $this->validateInput($this->project);
        return $this->request('GET', 'organizations/' . $this->organization . '/projects/' . $this->project . '/builds');
      }

      return $this->request('GET', 'organizations/' . $this->organization . '/builds');
    }

    return $this->request('GET', 'builds');
  }

  public function get($id)
  {
    $id = $this->validateInput($id, 'int');

    return $this->request('GET', 'organizations/' . $this->organization . '/projects/' . $this->project . '/builds/' . $id);
  }

  public function create(CreateBuild $data)
  {
    $url = 'organizations/' . $this->organization . '/projects/' . $this->project . '/builds';
    return $this->request('POST', $url, null, $data->toJson());
  }
}