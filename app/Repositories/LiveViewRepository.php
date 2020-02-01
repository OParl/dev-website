<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Repositories;


use App\Exceptions\LiveViewException;
use App\Model\LiveView;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

class LiveViewRepository
{
    const CACHE_DURATION = 43200; // Keep live views cached for half a day

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    public function __construct(CacheManager $cacheManager, Filesystem $filesystem, LoggerInterface $log)
    {
        $this->cacheManager = $cacheManager;
        $this->fs = $filesystem;
        $this->log = $log;
    }

    /**
     * @param $version
     * @return LiveView
     * @throws LiveViewException
     */
    public function get($version): LiveView
    {
        return $this->cacheManager->remember(
            'liveview.'.$version,
            self::CACHE_DURATION,
            function () use ($version) {
                if ($this->fs->exists('live/' . $version)) {
                    return new LiveView($this->fs, $this->log, $version);
                }

                throw LiveViewException::notLoadable($version);
            }
        );
    }
}
