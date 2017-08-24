<?php

namespace EFrane\Buildkite;

use EFrane\Buildkite\Clients\ClientFactory;

/**
 * Buildkite - API Entry Point.
 **/
class Buildkite
{
    /**
     * @var mixed|string
     **/
    protected $token = '';

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param null $organization
     *
     * @return mixed
     **/
    public function builds($organization = null)
    {
        return ClientFactory::make('build', $this->token, $organization);
    }

    /**
     * @param null $organization
     *
     * @return mixed
     **/
    public function projects($organization = null)
    {
        return ClientFactory::make('project', $this->token, $organization);
    }

    /**
     * @param null $organization
     *
     * @return mixed
     **/
    public function agents($organization = null)
    {
        return ClientFactory::make('agent', $this->token, $organization);
    }

    /**
     * @param null $organization
     *
     * @return mixed
     **/
    public function organizations($organization = null)
    {
        return ClientFactory::make('organization', $this->token, $organization);
    }

    /**
     * @param null $organization
     *
     * @return mixed
     **/
    public function jobs($organization = null)
    {
        return ClientFactory::make('job', $this->token, $organization);
    }

    /**
     * @return mixed
     **/
    public function emoji()
    {
        return ClientFactory::make('emoji', $this->token);
    }
}
