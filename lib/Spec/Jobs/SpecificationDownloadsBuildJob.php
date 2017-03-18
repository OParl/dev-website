<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 14:48
 */

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationDownloadsBuildJob extends Job
{
    protected $storageName = '';

    /**
     * @param Filesystem $fs
     * @param Log $log
     */
    public function handle(Filesystem $fs, Log $log)
    {
        $initialConstraint = $this->treeish;

        try {
            $hubSync = $this->updateRepository($fs, $log);
        } catch (\Exception $e) {
            $message = ':sos: Failed running make during downloads update';
            $this->notifySlack($message);
        }

        $this->storageName = 'master';
        if (strcmp($this->treeish, 'master') !== 0) {
            $this->storageName = substr($initialConstraint, 1);
        }

        $downloadsPath = $this->createDownloadsDirectory($fs);
        try {
            $this->provideDownloadableFiles($fs, $hubSync, $downloadsPath);
            $this->provideDownloadableArchives($fs, $hubSync, $downloadsPath);

            $message = ":white_check_mark: Updated specification downloads for %s to <https://github.com/OParl/spec/commit/%s|%s>";
            $this->notifySlack($message, $hubSync->getCurrentTreeish(), $hubSync->getCurrentHead(), $hubSync->getCurrentHead());
        } catch (\Exception $e) {
            $message = ":sos: Updating the downloads for %s failed!";
            $this->notifySlack($message, $this->treeish);
        }
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     * @return Repository
     */
    public function updateRepository(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($fs, $log);
        $this->checkoutHubSyncToTreeish($hubSync);

        $revision = $hubSync->getCurrentHead();

        if (!$this->runCleanRepositoryCommand($hubSync, 'make VERSION=%s clean archives', $revision)) {
            throw new \RuntimeException("Failed building the downloadables for {$revision}");
        }

        return $hubSync;
    }

    /**
     * @param Filesystem $fs
     * @param $currentHead
     * @return string
     */
    public function createDownloadsDirectory(Filesystem $fs)
    {
        $downloadsPath = 'downloads/specification/' . $this->storageName;

        if (!$fs->exists($downloadsPath)) {
            $fs->makeDirectory($downloadsPath);
            return $downloadsPath;
        }

        return $downloadsPath;
    }

    /**
     * @param Filesystem $fs
     * @param $currentHead
     * @param $hubSync
     * @param $downloadsPath
     */
    public function provideDownloadableFiles(Filesystem $fs, Repository $hubSync, $downloadsPath)
    {
        $downloadableFormats = [
            'pdf',
            'html',
            'epub',
            'odt',
            'docx',
            'txt',
        ];

        collect($downloadableFormats)->map(function ($format) use ($hubSync) {
            return [
                'build'   => 'OParl-' . $hubSync->getCurrentHead() . '.' . $format,
                'storage' => 'OParl-' . $this->storageName . '.' . $format,
            ];
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('out/' . $filename['build']),
                $downloadsPath . '/' . $filename['storage']
            );
        });
    }

    /**
     * @param Filesystem $fs
     * @param $currentHead
     * @param $hubSync
     * @param $downloadsPath
     */
    public function provideDownloadableArchives(Filesystem $fs, Repository $hubSync, $downloadsPath)
    {
        $downloadableArchives = [
            'zip',
            'tar.gz',
            'tar.bz2',
        ];

        collect($downloadableArchives)->map(function ($format) use ($hubSync) {
            return [
                'build'   => 'OParl-' . $hubSync->getCurrentHead() . '.' . $format,
                'storage' => 'OParl-' . $this->storageName . '.' . $format,
            ];
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('archives/' . $filename['build']),
                $downloadsPath . '/' . $filename['storage']
            );
        });
    }
}