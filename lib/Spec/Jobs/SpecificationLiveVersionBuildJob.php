<?php

namespace OParl\Spec\Jobs;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationLiveVersionBuildJob extends Job
{
    /**
     * @var array GH Push Hook Payload
     */
    protected $payload = [];

    /**
     * SpecificationBuildJob constructor.
     *
     * @param array $payload GH Push Hook Payload
     * @param string $treeish treeish of the synced repository
     */
    public function __construct(array $payload, $treeish = 'master')
    {
        $this->payload = $payload;

        $this->treeish = str_replace([',;\n'], '', $treeish);
    }

    /**
     * @param Filesystem $fs
     * @param Log $log
     */
    public function handle(Filesystem $fs, Log $log)
    {
        $hubSync = $this->getUpdatedHubSync($fs, $log);
        $path = $hubSync->getAbsolutePath();

        $this->checkoutHubSyncToTreeish($hubSync);

        // remove current html
        if ($fs->exists($hubSync->getPath() . '/out/live.html')) {
            $fs->delete($hubSync->getPath() . '/out/live.html');
        }

        $dockerCmd = "docker run --rm -v $(pwd):/spec -w /spec oparl/specbuilder:latest make live";

        if (!$this->runSynchronousJob($path, $dockerCmd)) {
            $log->error("Creating live version html failed");
        }

        // move html
        $fs->makeDirectory('live');
        $fs->put('live/live.html', $fs->get($hubSync->getPath() . '/out/live.html'));

        // reset and copy images
        $fs->delete($fs->files('live/images/'));
        $fs->deleteDirectory('live/images');
        $fs->makeDirectory('live/images');

        collect($fs->files($hubSync->getPath('/src/images')))->filter(function ($filename) {
            return ends_with($filename, '.png');
        })->map(function ($filename) use ($fs) {
            $fs->put('live/images/' . basename($filename), $fs->get($filename));
        });

        // provide concatenated markdown version
        $raw = collect($fs->files($hubSync->getPath('/src')))->filter(function ($filename) {
            return ends_with($filename, '.md');
        })->map(function ($filename) use ($fs) {
            return $fs->get($filename);
        })->reduce(function ($carry, $current) {
            return $carry . $current;
        }, '');

        $fs->put('live/raw.md', $raw);

        $fs->put('live/version.json', json_encode([
            'hash'     => $hubSync->getCurrentHead(),
            'official' => $hubSync->getCurrentTreeish(),
        ]));

        $message = ":white_check_mark: Updated live version to <https://github.com/OParl/spec/commit/%s|%s>";
        $this->notifySlack($message, $hubSync->getCurrentHead(), $hubSync->getCurrentHead());
    }
}

