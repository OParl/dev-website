<?php

namespace OParl\Spec\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use App\Services\OParlVersions;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

class SpecificationSchemaBuildJob extends SpecificationJob
{

    /**
     * Handle OParl Schema Updates.
     *
     * @param Filesystem      $fs
     * @param LoggerInterface $log
     */
    public function handle(Filesystem $fs, LoggerInterface $log)
    {
        try {
            $hubSync = $this->doSchemaUpdate($fs, $log);
            $hubSync->clean();

            $this->notify(
                SpecificationUpdateNotification::schemaUpdateSuccesfulNotification(
                    $this->treeish,
                    $hubSync->getCurrentHead()
                )
            );
        } catch (\Exception $e) {
            $this->notify(SpecificationUpdateNotification::schemaUpdateFailedNotification($this->treeish, $e->getMessage()));
        }
    }

    /**
     * @param Filesystem      $fs
     * @param LoggerInterface $log
     *
     * @return Repository
     */
    public function doSchemaUpdate(Filesystem $fs, LoggerInterface $log)
    {
        $hubSync = $this->getUpdatedHubSync($this->getRepository($fs), $log);

        $oparlVersions = new OParlVersions();
        $dirname = $oparlVersions->getVersionForConstraint('specification', $this->treeish);
        $log->info("Beginning Schema Update Job for treeish {$this->treeish}");

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

        $dirname = $this->createSchemaDirectory($fs, $dirname);

        collect($fs->files($hubSync->getPath('schema/')))->each(function ($file) use ($fs, $dirname) {
            $filename = $dirname.'/'.basename($file);

            $fs->put($filename, $fs->get($file));
        });

        $log->info("Finished Schema Update Job for treeish {$this->treeish}");

        return $hubSync;
    }

    /**
     * Create the versioned Schema Directory.
     *
     * Schema directories
     *
     * @param Filesystem $fs
     * @param $authoritativeVersion
     *
     * @return string
     */
    public function createSchemaDirectory(Filesystem $fs, $authoritativeVersion)
    {
        $schemaPath = 'schema/'.$authoritativeVersion;

        if (!$fs->exists($schemaPath)) {
            $fs->makeDirectory($schemaPath);

            return $schemaPath;
        }

        return $schemaPath;
    }
}
