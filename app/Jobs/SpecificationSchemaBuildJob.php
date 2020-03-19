<?php

namespace App\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use App\Services\HubSync\Repository;
use App\Services\OParlVersions;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

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
        $authoritativeVersion = $oparlVersions->getVersionForConstraint('specification', $this->treeish);
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

        $schemaDir = $this->createOrPruneSchemaDirectory($fs, $authoritativeVersion);

        $needsStringReplacement = false;

        collect($fs->files($hubSync->getPath('schema/')))
            ->filter(function ($file) use (&$needsStringReplacement) {
                if ('strings.yml' !== basename($file)) {
                    return true;
                }

                $needsStringReplacement = true;
                return false;
            })
            ->each(function ($file) use ($fs, $schemaDir) {
            $filename = $schemaDir.'/'.basename($file);

            $fs->put($filename, $fs->get($file));
        });

        if ($needsStringReplacement) {
            $this->translateSrings($hubSync, $fs, $schemaDir);
        }

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
    public function createOrPruneSchemaDirectory(Filesystem $fs, $authoritativeVersion)
    {
        $schemaPath = 'schema/'.$authoritativeVersion;

        if ($fs->exists($schemaPath)) {
            $fs->deleteDirectory($schemaPath);
        }

        $fs->makeDirectory($schemaPath);

        return $schemaPath;
    }

    /**
     * @param Repository $hubSync
     * @param string     $schemaDir
     */
    protected function translateSrings(Repository $hubSync, Filesystem $fs, string $schemaDir)
    {
        $strings = $this->loadStrings($hubSync, $fs);

        collect($fs->files($schemaDir))
            ->each(function ($schemaFile) use ($fs, $strings) {
                $content = $fs->get($schemaFile);

                preg_match_all('/\{\{.+\}\}/i', $content, $translatables);

                foreach ($translatables[0] as $translatable) {
                    $translatableKey = trim(str_replace(['{{', '}}'], '', $translatable));

                    if (array_key_exists($translatableKey, $strings)) {
                        $translated = addslashes(str_replace("\n", '\n', trim($strings[$translatableKey])));
                        $content = str_replace($translatable, $translated, $content);
                    }
                }

                $fs->put($schemaFile, $content);
            });


    }

    protected function loadStrings(Repository $hubSync, Filesystem $fs)
    {
        $stringsFile = $hubSync->getPath('schema/strings.yml');
        // This is going to break eventually when we have multiple translations
        $strings = Yaml::parse($fs->get($stringsFile))['de'];

        return $strings;
    }
}
