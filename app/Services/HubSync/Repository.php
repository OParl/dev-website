<?php

namespace App\Services\HubSync;

use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Class Repository.
 *
 * This is a git repository container able to sync itself with any remote.
 * Syncing is done using the actual git binaries thus the process running
 * this command should be allowed to use these.
 */
class Repository
{
    use SynchronousProcess;

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
     * Repository constructor.
     *
     * @param Filesystem $fs
     * @param string     $localName
     * @param string     $remoteURI
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
        if (!$this->exists()) {
            $cmd = sprintf(
                'git clone -q --recursive --recurse-submodules %s %s',
                $this->remoteURI,
                $this->absolutePath
            );
        } else {
            $cmd = sprintf('git -C %s pull -q', $this->absolutePath);
        }

        $process = new Process($cmd);

        $process->setTimeout(900);
        $process->start();
        $process->wait();

        return $process->getExitCode() == 0;
    }

    /**
     * Removes the repository.
     */
    public function remove()
    {
        $this->fs->deleteDirectory($this->path);
        $this->fs->delete($this->path);
    }

    public function clean()
    {
        return $this->synchronousProcess(sprintf('git -C %s clean -xf', $this->getAbsolutePath()));
    }

    /**
     * Get the path to a file inside the repository.
     *
     * Will return the repository's root path if no
     * filename was given.
     *
     * @param string $file path/to/a/file
     *
     * @return string
     */
    public function getPath($file = '')
    {
        return (strlen($file) === 0) ? $this->path : $this->path.'/'.$file;
    }

    /**
     * Get the repository name.
     *
     * i.e. the name of the repository's root directory
     *
     * @return string
     */
    public function getLocalName()
    {
        return $this->localName;
    }

    /**
     * Get the remote URI used for updates.
     *
     * @return string
     */
    public function getRemoteURI()
    {
        return $this->remoteURI;
    }

    /**
     * Get the current repo treeish.
     *
     * Aside from the canonical revision hash, there are
     * also other ways to uniquely address a git commit.
     *
     * This method will return the shortest possible
     * string that uniquely identifies the current HEAD.
     * Typically, that should be the name of a branch or
     * a checked-out non-HEAD tag.
     *
     * @return string
     */
    public function getCurrentTreeish()
    {
        $revParseCmd = sprintf('git -C %s rev-parse --abbrev-ref HEAD', $this->getAbsolutePath());

        return $this->synchronousProcess($revParseCmd);
    }

    /**
     * Returns the absolute path to the repository.
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * @return string unique shortened commit hash for HEAD
     */
    public function getCurrentHead()
    {
        return $this->getUniqueRevision('HEAD');
    }

    /**
     * Get the unique shortened commit hash for any revision.
     *
     * In contrast to `getCurrentTreeish` this does not
     * return a symbolic name but the familiar git commit sha1
     * hash in it's shortened version.
     *
     * @param string $revision revision can be any tree-ish
     *
     * @return string
     */
    public function getUniqueRevision($revision)
    {
        $revParseCmd = sprintf('git -C %s rev-parse --short %s', $this->getAbsolutePath(), $revision);

        return $this->synchronousProcess($revParseCmd);
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->fs->exists("{$this->path}/.git/HEAD");
    }
}
