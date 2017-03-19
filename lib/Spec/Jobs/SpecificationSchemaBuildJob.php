<?php

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationSchemaBuildJob extends Job
{
    public function handle(Filesystem $fs, Log $log)
    {
        try {
            $hubSync = $this->doSchemaUpdate($fs, $log);
            $hubSync->clean();

            $this->notifySlack(
                ":white_check_mark: Updated schema assets for %s to <https://github.com/OParl/spec/commit/%s|%s>",
                $this->treeish,
                $hubSync->getCurrentHead()
            );
        } catch (\Exception $e) {
            $this->notifySlack(
                ":sos: Schema assets update for %s failed!",
                $this->treeish
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

        $initialConstraint = $this->treeish;

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
            $dirname = substr($initialConstraint, 1);
        }

        $dirname = $this->createSchemaDirectory($fs, $dirname);

        collect($fs->files($hubSync->getPath('schema/')))->each(function ($file) use ($fs, $dirname) {
            $filename = $dirname . '/' . basename($file);

            $fs->put($filename, $fs->get($file));
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