<?php

namespace OParl\Spec\Jobs;

use App\Notifications\SpecificationUpdateNotification;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationLiveVersionBuildJob extends SpecificationJob
{
    /**
     * @param Filesystem $fs
     * @param Log        $log
     */
    public function handle(Filesystem $fs, Log $log)
    {
        try {
            $hubSync = $this->doUpdate($fs, $log);

            $this->notify(SpecificationUpdateNotification::liveVersionUpdateSuccessfulNotification(
                $this->treeish,
                $hubSync->getCurrentHead()
            ));
        } catch (\RuntimeException $e) {
            $this->notify(SpecificationUpdateNotification::liveVersionUpdateFailedNotification($this->treeish));
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
        $fs->makeDirectory('live');
        $newVersion = sprintf('%s/%s.html',
            $hubSync->getPath($this->buildDir),
            $this->buildBasename
        );

        if ($fs->exists($newVersion)) {
            $fs->delete('live/live.html');
            $fs->copy($newVersion, 'live/live.html');
        }
    }

    /**
     * @param Filesystem $fs
     * @param            $hubSync
     */
    protected function updateImages(Filesystem $fs, Repository $hubSync)
    {
        $fs->delete($fs->files('live/images/'));
        $fs->deleteDirectory('live/images');
        $fs->makeDirectory('live/images');

        collect($fs->files($hubSync->getPath('/build/src/images')))->filter(function ($filename) {
            return ends_with($filename, '.png');
        })->map(function ($filename) use ($fs) {
            $fs->put('live/images/'.basename($filename), $fs->get($filename));
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
            return $carry.$current;
        }, '');

        $fs->put('live/raw.md', $raw);
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

        $fs->put('live/version.json', json_encode([
            'hash'     => $hubSync->getCurrentHead(),
            'official' => $official,
        ]));
    }
}
