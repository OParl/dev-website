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
    /**
     * @param Filesystem $fs
     * @param Log $log
     */
    public function handle(Filesystem $fs, Log $log)
    {
        list($hubSync, $currentHead) = $this->updateRepository($fs, $log);

        $downloadsPath = $this->createDownloadsDirectory($fs, $hubSync->getUniqueRevision($currentHead));
        try {
            $this->provideDownloadableFiles($fs, $currentHead, $hubSync, $downloadsPath);
            $this->provideDownloadableArchives($fs, $currentHead, $hubSync, $downloadsPath);

            $message = ":white_check_mark: Updated specification downloads to <https://github.com/OParl/spec/commit/%s|%s>";
            $this->notifySlack($message, $currentHead, $currentHead);
        } catch (\Exception $e) {
            $message = ":sos: Updating the downloads for %s failed!";
            $this->notifySlack($message, $this->treeish);
        }
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     * @return array
     */
    public function updateRepository(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($fs, $log);
        $this->checkoutHubSyncToTreeish($hubSync);

        $dockerCmd = $this->prepareCommand(sprintf('make VERSION=%s clean archives',
            $hubSync->getUniqueRevision($this->treeish)));
        $log->info($dockerCmd);

        if (!$this->runSynchronousJob($hubSync->getAbsolutePath(), $dockerCmd)) {
            $log->error('Updating the downloadables failed');
            return [$hubSync, $this->treeish];
        }

        return [$hubSync, $this->treeish];
    }

    /**
     * @param Filesystem $fs
     * @param $currentHead
     * @return string
     */
    public function createDownloadsDirectory(Filesystem $fs, $hash)
    {
        $downloadsPath = 'downloads/specification/' . $hash;

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
    public function provideDownloadableFiles(Filesystem $fs, $currentHead, Repository $hubSync, $downloadsPath)
    {
        $downloadableFormats = [
            'pdf',
            'html',
            'epub',
            'odt',
            'docx',
            'txt',
        ];

        $hash = $hubSync->getUniqueRevision($currentHead);

        collect($downloadableFormats)->map(function ($format) use ($hash) {
            return 'OParl-' . $hash . '.' . $format;
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('out/' . $filename),
                $downloadsPath . '/' . $filename
            );
        });
    }

    /**
     * @param Filesystem $fs
     * @param $currentHead
     * @param $hubSync
     * @param $downloadsPath
     */
    public function provideDownloadableArchives(Filesystem $fs, $currentHead, Repository $hubSync, $downloadsPath)
    {
        $downloadableArchives = [
            'zip',
            'tar.gz',
            'tar.bz2',
        ];

        $hash = $hubSync->getUniqueRevision($currentHead);

        collect($downloadableArchives)->map(function ($format) use ($hash) {
            return 'OParl-' . $hash . '.' . $format;
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('archives/' . $filename),
                $downloadsPath . '/' . $filename
            );
        });
    }
}