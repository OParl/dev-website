<?php

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
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
        try {
            $hubSync = $this->doSchemaUpdate($fs, $log);
            $this->notifySlack(
                ":white_check_mark: Updated schema assets for %s to %s",
                $this->constraint,
                $hubSync->getCurrentHead()
            );
        } catch (\Exception $e) {
            $this->notifySlack(
                ":sos: Schema assets update for %s failed!",
                $this->constraint
            );
        }
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     * @return Repository
     */
    public function doSchemaUpdate(Filesystem $fs, Log $log)
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

        return $hubSync;
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