<?php namespace EFrane\Buildkite\Clients;

class OrganizationClient extends AbstractClient
{
  public function __construct($token, $id = null)
  {
    parent::__construct($token, $id);

    return (is_null($id)) ? $this->index() : $this->get($id);
  }

  public function get($name)
  {
    $this->validateInput($name);
    return $this->request('GET', 'organizations/'.$name);
  }

  public function index()
  {
    return $this->request('GET', 'organizations');
  }
}