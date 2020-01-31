<?php

namespace App\Services\HubSync;

use Composer\Semver\Semver;
use Illuminate\Support\Collection;

class RepositoryVersions
{
    use SynchronousProcess;

    /**
     * @var Repository
     */
    protected $repository = null;

    /**
     * @var Collection
     */
    protected $versions = null;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->loadVersions();
    }

    public static function forRepository(Repository $repository)
    {
        return new self($repository);
    }

    public function getAll()
    {
        return $this->versions;
    }

    /**
     * @param $constraint
     *
     * @throws \UnexpectedValueException
     *
     * @return mixed
     */
    public function getLatestMatchingConstraint($constraint)
    {
        $matchingVersions = $this->versions->filter(function ($versionToCheck) use ($constraint) {
            return Semver::satisfies($versionToCheck, $constraint);
        });

        return $matchingVersions->last();
    }

    public function loadVersions()
    {
        $cmd = sprintf('git -C %s tag', $this->repository->getAbsolutePath());
        $output = $this->synchronousProcess($cmd);

        $this->versions = collect(explode("\n", $output))->filter(function ($versionToCheck) {
            $semverRegex = '/v?\d+\.\d+(?:\.\d+)?(?:-[a-z0-9_+]+)?/';

            return preg_match($semverRegex, $versionToCheck);
        });

        $this->versions->push('master');
    }
}
