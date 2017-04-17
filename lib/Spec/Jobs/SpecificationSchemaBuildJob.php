<?php

namespace OParl\Spec\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationSchemaBuildJob extends Job
{
    protected $initialConstraint;

    /**
     * Handle OParl Schema Updates
     *
     * @param Filesystem $fs
     * @param Log $log
     */
    public function handle(Filesystem $fs, Log $log)
    {
        try {
            $hubSync = $this->doSchemaUpdate($fs, $log);
            $hubSync->clean();

            $this->notify(SpecificationUpdateNotification::schemaUpdateSuccesfulNotification(
                $this->initialConstraint,
                $hubSync->getCurrentHead()
            ));
        } catch (\Exception $e) {
            $this->notify(SpecificationUpdateNotification::schemaUpdateFailedNotification($this->treeish));
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

        $this->initialConstraint = $this->treeish;
        $log->info("Beginning Schema Update Job for treeish {$this->initialConstraint}");

        try {
            if (!$this->checkoutHubSyncToTreeish($hubSync)) {
                $log->info('Did not switch branches');
            } else {
                $log->info('Did switch branches');
            }
        } catch (\RuntimeException $e) {
            $log->error('Branch switch on schema update failed');
            throw $e;
        }

        $dirname = 'master';
        if (strcmp($this->treeish, 'master') !== 0) {
            $dirname = substr($this->initialConstraint, 1);
        }

        $dirname = $this->createSchemaDirectory($fs, $dirname);

        collect($fs->files($hubSync->getPath('schema/')))->each(function ($file) use ($fs, $dirname) {
            $filename = $dirname . '/' . basename($file);

            $fs->put($filename, $fs->get($file));
        });

        $log->info("Finished Schema Update Job for treeish {$this->initialConstraint}");

        return $hubSync;
    }

    /**
     * Create the versioned Schema Directory
     *
     * Schema directories
     *
     * @param Filesystem $fs
     * @param $authoritativeVersion
     * @return string
     */
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