<?php namespace EFrane\Buildkite\Clients;

use EFrane\Buildkite\BuildkiteException;
use Guzzle\Http\Client;

abstract class AbstractClient
{
    protected $apiVersion = 'v1';

    protected $baseURL = 'https://api.buildkite.com';

    protected $guzzleClient = null;

    protected $organization = null;
    protected $project = null;

    protected $token = null;

    public function __construct($token, $organizationAndProject = null)
    {
        $this->token = $token;

        $organizationAndProject = $this->validateInput($organizationAndProject);

        $this->extractCombinedOrganizationFromProject($organizationAndProject);

        $this->guzzleClient = new Client($this->getBaseURL(), []);
    }

    public function organization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    public function project($project)
    {
        $this->extractCombinedOrganizationFromProject($project);
        return $this;
    }

    private function getBaseURL()
    {
        return $this->baseURL . '/' . $this->apiVersion . '/';
    }

    protected function prepareURL($path, array $arguments)
    {
        $arguments = implode('&', $arguments);
        return sprintf('/%s/%s?%s', $this->apiVersion, $path, $arguments);
    }

    protected function request($method, $uri, array $arguments = [], $headers = null, $body = null, $options = [])
    {
        $request = $this->guzzleClient->createRequest($method, $uri, $headers, $body, $options);
        $request->getQuery()->add('access_token', $this->token);

        foreach ($arguments as $argument => $value) {
            if (is_null($value)
      ||  strlen($value) == 0
      ||  count($value) == 0) {
                continue;
            }

            if (is_array($value)) {
                collect($value)->map(function ($val, $key) use ($argument) {
          return [
            sprintf('%s[%s]', $argument, $key),
            urlencode($val)
          ];
        })->each(function ($encoded) use ($request) {
          $request->getQuery()->add($encoded[0], $encoded[1]);
        });
            } else {
                $request->getQuery()->add($argument, $value);
            }
        }

        return $request->send()->json();
    }

    protected function validateInput($id, $type = 'string')
    {
        if (is_null($id)) {
            return null;
        }

        $validationMethod = sprintf('is_%s', $type);
        if (!call_user_func($validationMethod, $id)) {
            throw new BuildkiteException(sprintf("ID must be %s", ucfirst($type)));
        }

        return $id;
    }

  /**
   * @param $project
   **/
  protected function extractCombinedOrganizationFromProject($project)
  {
      if (strpos($project, '/') > 0) {
          list($this->organization, $this->project) = explode('/', $project);
      } else {
          $this->organization = $project;
      }
  }

  // TODO: pagination methods
}
