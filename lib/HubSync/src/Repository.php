<?php

namespace EFrane\HubSync;

use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Class Repository
 *
 * This is a repository container able to sync itself with any remote.
 * Syncing is done using the actual git binaries thus the process running
 * this command should be allowed to use these.
 *
 * @package EFrane\HubSync
 */
class Repository
{
    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $absolutePath = '';

    /**
     * @var string
     */
    protected $localName = '';

    /**
     * @var string
     */
    protected $remoteURI = '';

    protected $fs = null;

    /**
     * Repository constructor
     *
     * @param $localName string
     * @param $remoteURI string
     */
    public function __construct(Filesystem $fs, $localName, $remoteURI)
    {
        $this->fs = $fs;

        $this->localName = $localName;
        $this->remoteURI = $remoteURI;

        $this->path = "hub_sync/{$this->localName}";
        $this->absolutePath = storage_path("app/{$this->path}");

        if (!$fs->exists('hub_sync')) {
            $fs->makeDirectory('hub_sync');
        }
    }

    /**
     * @return bool was the update succesful?
     */
    public function update()
    {
        if (!$this->fs->exists("{$this->path}/.git/HEAD")) {
            $cmd = sprintf(
                'git clone -q --recursive --recurse-submodules %s %s',
                $this->remoteURI,
                $this->absolutePath
            );
        } else {
            $cmd = sprintf('git -C %s pull -q --autostash --rebase', $this->absolutePath);
        }

        $process = new Process($cmd);

        $process->setTimeout(900);
        $process->start();
        $process->wait();

        return $process->getExitCode() == 0;
    }

    public function clean()
    {
        $this->fs->deleteDirectory($this->path);
        $this->fs->delete($this->path);
    }

    /**
     * @return string
     */
    public function getPath($file = '')
    {
        return (strlen($file) === 0) ? $this->path : $this->path . '/' . $file;
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * @return string
     */
    public function getLocalName()
    {
        return $this->localName;
    }

    /**
     * @return string
     */
    public function getRemoteURI()
    {
        return $this->remoteURI;
    }

    public function getCurrentTreeish()
    {
        $revParseCmd = sprintf('git -C %s rev-parse --abbrev-ref HEAD', $this->getAbsolutePath());
        $process = new Process($revParseCmd);

        $process->start();
        $process->wait();

        return trim($process->getOutput());
    }

    public function getCurrentHead()
    {
        $revParseCmd = sprintf('git -C %s rev-parse --short HEAD', $this->getAbsolutePath());

        $process = new Process($revParseCmd);

        $process->start();
        $process->wait();

        return trim($process->getOutput());
    }
}