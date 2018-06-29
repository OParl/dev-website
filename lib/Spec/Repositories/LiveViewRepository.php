<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace OParl\Spec\Repositories;


use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use OParl\Spec\Model\LiveView;

class LiveViewRepository
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var Log
     */
    protected $log;

    public function __construct(Filesystem $filesystem, Log $log)
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
