<?php

namespace OParl\Spec\Jobs;

use App\Notifications\SpecificationUpdateNotification;
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
     * @return \EFrane\HubSync\Repository
     */
    public function doUpdate(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($this->getRepository($fs), $log);
        $this->checkoutHubSyncToTreeish($hubSync);

        if (!$this->runRepositoryCommand($hubSync, 'make live')) {
            $log->error('Creating live version html failed');
            throw new \RuntimeException('Update failed');
        }

        // move html
        $fs->makeDirectory('live');
        $fs->put('live/live.html', $fs->get($hubSync->getPath().'/out/live.html'));

        // reset and copy images
        $fs->delete($fs->files('live/images/'));
        $fs->deleteDirectory('live/images');
        $fs->makeDirectory('live/images');

        collect($fs->files($hubSync->getPath('/src/images')))->filter(function ($filename) {
            return ends_with($filename, '.png');
        })->map(function ($filename) use ($fs) {
            $fs->put('live/images/'.basename($filename), $fs->get($filename));
        });

        // provide concatenated markdown version
        $raw = collect($fs->files($hubSync->getPath('/src')))->filter(function ($filename) {
            return ends_with($filename, '.md');
        })->map(function ($filename) use ($fs) {
            return $fs->get($filename);
        })->reduce(function ($carry, $current) {
            return $carry.$current;
        }, '');

        $fs->put('live/raw.md', $raw);

        $official = $hubSync->getCurrentTreeish();
        if ($official === 'HEAD') {
            $official = $this->treeish;
        }

        $fs->put('live/version.json', json_encode([
            'hash'     => $hubSync->getCurrentHead(),
            'official' => $official,
        ]));

        // IMPORTANT: Need to clean up by hand since images are missing otherwise
        $hubSync->clean();

        return $hubSync;
    }
}
