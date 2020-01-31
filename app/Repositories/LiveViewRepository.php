<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Repositories;


use App\Model\LiveView;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

class LiveViewRepository
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var LoggerInterface
     */
    protected $log;

    public function __construct(Filesystem $filesystem, LoggerInterface $log)
    {
        $this->fs = $filesystem;
        $this->log = $log;
    }

    public function get($version)
    {
        if ($this->fs->exists('live/' . $version)) {
            return new LiveView($this->fs, $this->log, $version);
        }

        // TODO: throw suitable exception
        return null;
    }
}
