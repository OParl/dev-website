<?php

namespace OParl\Spec\Jobs;

use EFrane\HubSync\RepositoryVersions;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationSchemaBuildJob extends Job
{
    protected $constraint = '';

    /**
     * SpecificationSchemaBuildJob constructor.
     *
     * Version constraints currently need to be given in the form of
     * ~<major>.<minor>, e.g. ~1.0 to get the latest 1.0.x tagged version
     *
     * @param string $constraint semver version constraint
     */
    public function __construct($constraint = 'master')
    {
        $this->constraint = $constraint;
    }

    public function handle(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($fs, $log);
        $versions = RepositoryVersions::forRepository($hubSync);
        $this->treeish = $versions->getLatestMatchingConstraint($this->constraint);

        $this->checkoutHubSyncToTreeish($hubSync);

        $dirname = (strcmp('master', $this->constraint) === 0) ? $this->constraint : substr($this->constraint, 1);
        $dirname = $this->createSchemaDirectory($fs, $dirname);

        collect($fs->files($hubSync->getPath('schema/')))->each(function ($file) use ($fs, $dirname) {
            $filename = $dirname . '/' . basename($file);

            if ($fs->exists($filename)) {
                $fs->delete($filename);
            }

            $fs->copy($file, $filename);
        });
        /**
         * Schema files are stored under app/schema
         *
         * Sub directory naming is as follows:
         *
         * - master contains the schema/ contents of the current master branch HEAD
         * - <major>.<minor> contain the latest tagged version of the schema/ contents
         * - patch and lower version denominators are assigned to their corresponding minor
         * TODO: make a command that fetches all currently required schema files (master HEAD, published minors)
         */

        // TODO: adjust schema controller to query from there

    }

    public function createSchemaDirectory(Filesystem $fs, $authoritativeVersion)
    {
        $schemaPath = 'schema/' . $authoritativeVersion;

        if (!$fs->exists($schemaPath)) {
            $fs->makeDirectory($schemaPath);
            return $schemaPath;
        }

        return $schemaPath;
    }
}