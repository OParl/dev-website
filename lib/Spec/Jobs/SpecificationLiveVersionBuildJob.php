<?php

namespace OParl\Spec\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\OParlVersions;
use Psr\Log\LoggerInterface;

class SpecificationLiveVersionBuildJob extends SpecificationJob
{
    protected $storageName = '';

    /**
     * @param Filesystem      $fs
     * @param LoggerInterface $log
     */
    public function handle(Filesystem $fs, LoggerInterface $log)
    {
        try {
            $oparlVersions = new OParlVersions();
            $this->storageName = $oparlVersions->getVersionForConstraint('specification', $this->treeish);

            $hubSync = $this->doUpdate($fs, $log);

            $this->notify(
                SpecificationUpdateNotification::liveVersionUpdateSuccessfulNotification(
                    $this->treeish,
                    $hubSync->getCurrentHead()
            ));
        } catch (\RuntimeException $e) {
            $this->notify(SpecificationUpdateNotification::liveVersionUpdateFailedNotification($this->treeish,
                $e->getMessage()));
        }
    }

    /**
     * @param Filesystem $fs
     * @param Log        $log
     *
     * @throws
     *
     * @return \EFrane\HubSync\Repository
     */
    public function doUpdate(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($this->getRepository($fs), $log);
        $this->checkoutHubSyncToTreeish($hubSync);
        $this->getBuildMeta($hubSync);

        if (!$this->runRepositoryCommand($hubSync, 'python3 build.py live')) {
            $log->error('Creating live version html failed');

            throw new \RuntimeException('Update failed');
        }

        $this->updateHTML($fs, $hubSync);
        $this->updateImages($fs, $hubSync);
        $this->updateConcatenatedMarkdownVersion($fs, $hubSync);
        $this->updateVersionMetaInfo($fs, $hubSync);

        // IMPORTANT: Need to clean up by hand since images are missing otherwise
        $hubSync->clean();

        return $hubSync;
    }

    /**
     * @param Filesystem $fs
     * @param            $hubSync
     */
    protected function updateHTML(Filesystem $fs, $hubSync)
    {
        $fs->makeDirectory($this->getStoragePath(''));
        $newVersion = sprintf('%s/%s.html',
            $hubSync->getPath($this->buildDir),
            $this->buildBasename
        );

        if ($fs->exists($newVersion)) {
            $fs->delete($this->getStoragePath('live.html'));
            $fs->copy($newVersion, $this->getStoragePath('live.html'));
        }
    }

    protected function getStoragePath($path)
    {
        if (starts_with($path, '/')) {
            $path = substr($path, 1);
        }

        return sprintf('live/%s/%s', $this->storageName, $path);
    }

    /**
     * @param Filesystem $fs
     * @param            $hubSync
     */
    protected function updateImages(Filesystem $fs, Repository $hubSync)
    {
        $fs->delete($fs->files($this->getStoragePath('images/')));
        $fs->deleteDirectory($this->getStoragePath('images'));
        $fs->makeDirectory($this->getStoragePath('images'));

        collect($fs->files($hubSync->getPath('/build/src/images')))->filter(function ($filename) {
            return ends_with($filename, '.png');
        })->map(function ($filename) use ($fs) {
            $fs->put($this->getStoragePath('images/' . basename($filename)), $fs->get($filename));
        });
    }

    /**
     * @param Filesystem $fs
     * @param            $hubSync
     */
    protected function updateConcatenatedMarkdownVersion(Filesystem $fs, Repository $hubSync)
    {
        $raw = collect($fs->files($hubSync->getPath('/src')))->filter(function ($filename) {
            return ends_with($filename, '.md');
        })->map(function ($filename) use ($fs) {
            return $fs->get($filename);
        })->reduce(function ($carry, $current) {
            return $carry . $current;
        }, '');

        $fs->put($this->getStoragePath('raw.md'), $raw);
    }

    /**
     * @param Filesystem $fs
     * @param            $hubSync
     */
    protected function updateVersionMetaInfo(Filesystem $fs, Repository $hubSync)
    {
        $official = $hubSync->getCurrentTreeish();
        if ($official === 'HEAD') {
            $official = $this->treeish;
        }

        $fs->put($this->getStoragePath('version.json'), json_encode([
            'hash'     => $hubSync->getCurrentHead(),
            'official' => $official,
        ]));
    }
}
