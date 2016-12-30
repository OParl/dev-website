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
    use SynchronousProcess;

    protected $treeish = '';

    public function __construct($treeish = 'master')
    {
        $this->treeish = $treeish;
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     */
    public function handle(Filesystem $fs, Log $log)
    {
        list($hubSync, $currentHead) = $this->updateRepository($fs, $log);

        $downloadsPath = $this->createDownloadsDirectory($fs, $currentHead);

        $this->provideDownloadableFiles($fs, $currentHead, $hubSync, $downloadsPath);
        $this->provideDownloadableArchives($fs, $currentHead, $hubSync, $downloadsPath);

        $message = ":white_check_mark: Updated specification downloads to <https://github.com/OParl/spec/commit/%s|%s>";
        \Slack::send(sprintf($message, $currentHead, $currentHead));
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     * @return array
     */
    public function updateRepository(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($fs, $log);
        $version = $hubSync->getUniqueRevision($this->treeish);

        $dockerCmd = sprintf(
            'docker run --rm -v $(pwd):/spec -w /spec oparl/specbuilder:latest make VERSION=%s clean archives',
            $version
        );

        if (!$this->runSynchronousJob($hubSync->getAbsolutePath(), $dockerCmd)) {
            $log->error('Updating the downloadables failed');
            return [$hubSync, $version];
        }

        return [$hubSync, $version];
    }

    /**
     * @param Filesystem $fs
     * @param $currentHead
     * @return string
     */
    public function createDownloadsDirectory(Filesystem $fs, $currentHead)
    {
        $downloadsPath = 'downloads/specification/' . $currentHead;

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
    public function provideDownloadableFiles(Filesystem $fs, $currentHead, $hubSync, $downloadsPath)
    {
        $downloadableFormats = [
            'pdf',
            'html',
            'epub',
            'odt',
            'docx',
            'txt',
        ];

        collect($downloadableFormats)->map(function ($format) use ($currentHead) {
            return 'OParl-' . $currentHead . '.' . $format;
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
    public function provideDownloadableArchives(Filesystem $fs, $currentHead, $hubSync, $downloadsPath)
    {
        $downloadableArchives = [
            'zip',
            'tar.gz',
            'tar.bz2',
        ];

        collect($downloadableArchives)->map(function ($format) use ($currentHead) {
            return 'OParl-' . $currentHead . '.' . $format;
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('archives/' . $filename),
                $downloadsPath . '/' . $filename
            );
        });
    }
}