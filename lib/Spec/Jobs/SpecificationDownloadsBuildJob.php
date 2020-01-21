<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 14:48.
 */

namespace OParl\Spec\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\OParlVersions;
use Psr\Log\LoggerInterface;

class SpecificationDownloadsBuildJob extends SpecificationJob
{
    protected $storageName = '';

    /**
     * @param Filesystem      $fs
     * @param LoggerInterface $log
     *
     * @throws \Exception
     */
    public function handle(Filesystem $fs, LoggerInterface $log)
    {
        $oparlVersions = new OParlVersions();
        $this->storageName = $oparlVersions->getVersionForConstraint('specification', $this->treeish);

        try {
            $hubSync = $this->build($fs, $log);
            $this->getBuildMeta($hubSync);
        } catch (\Exception $e) {
            $this->notify(
                SpecificationUpdateNotification::downloadsUpdateFailedNotification(
                    $this->treeish,
                    $e->getMessage()
                )
            );

            throw $e;
        }

        $downloadsPath = $this->createDownloadsDirectory($fs);

        try {
            $this->provideDownloadableFiles($fs, $hubSync, $downloadsPath);
            $this->provideDownloadableArchives($fs, $hubSync, $downloadsPath);

            $this->notify(SpecificationUpdateNotification::downloadsUpdateSuccesfulNotification(
                $this->treeish,
                $hubSync->getCurrentHead()
            ));
        } catch (\Exception $e) {
            $this->notify(
                SpecificationUpdateNotification::downloadsUpdateFailedNotification(
                    $this->treeish,
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * @param Filesystem $fs
     * @param Log        $log
     *
     * @return Repository
     * @throws \ReflectionException
     */
    public function build(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($this->getRepository($fs), $log);
        $this->checkoutHubSyncToTreeish($hubSync);

        $revision = $hubSync->getCurrentHead();

        if (!$this->runCleanRepositoryCommand($hubSync, 'python3 build.py archives', $revision)) {
            throw new \RuntimeException("Failed building the archives for {$revision}");
        }

        return $hubSync;
    }

    /**
     * @param Filesystem $fs
     *
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
     * @param            $hubSync
     * @param            $downloadsPath
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
                'build'   => sprintf('%s/%s.%s', $this->buildDir, $this->buildBasename, $format),
                'storage' => sprintf('OParl-%s.%s', $this->storageName, $format),
            ];
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->delete($downloadsPath . '/' . $filename['storage']);
            $fs->copy(
                $hubSync->getPath($filename['build']),
                $downloadsPath . '/' . $filename['storage']
            );
        });
    }

    /**
     * @param Filesystem $fs
     * @param            $hubSync
     * @param            $downloadsPath
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
                'build'   => sprintf('%s.%s', $this->buildDir, $format),
                'storage' => sprintf('OParl-%s.%s', $this->storageName, $format),
            ];
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->delete($downloadsPath . '/' . $filename['storage']);
            $fs->copy(
                $hubSync->getPath($filename['build']),
                $downloadsPath . '/' . $filename['storage']
            );
        });
    }
}
