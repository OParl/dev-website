<?php

namespace OParl\Spec\Jobs;

use Composer\Semver\Semver;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationSchemaBuildJob extends Job
{
    public function __construct($treeish = 'master')
    {
        $this->treeish = $treeish;
    }

    public function handle(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($fs, $log);
        $this->checkoutHubSyncToTreeish($hubSync);

        $this->createSchemaDirectory($fs, $this->treeish);

        // TODO: move schema files to persistent location
        if ()
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