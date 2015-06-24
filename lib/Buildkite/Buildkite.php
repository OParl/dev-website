<?php namespace EFrane\Buildkite;

use EFrane\Buildkite\Clients\ClientFactory;

class Buildkite
{
  protected $token = '';

  public function __construct()
  {
    $this->token = config('services.buildkite.access_token');
  }

  public function builds($organization = null)
  {
    return ClientFactory::make('build', $this->token, $organization);
  }

  public function projects($organization = null)
  {
    return ClientFactory::make('project', $this->token, $organization);
  }

  public function agents($organization = null)
  {
    return ClientFactory::make('agent', $this->token, $organization);
  }

  public function organizations($organization = null)
  {
    return ClientFactory::make('organization', $this->token, $organization);
  }

  public function jobs($organization = null)
  {
    return ClientFactory::make('job', $this->token, $organization);
  }

  public function emoji()
  {
    return ClientFactory::make('emoji', $this->token);
  }
}
