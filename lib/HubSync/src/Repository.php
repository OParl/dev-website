<?php

namespace EFrane\HubSync;

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
    protected $storagePath;

    /**
     * @var string
     */
    protected $localName = '';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $remoteURI = '';

    /**
     * Repository constructor
     *
     * @param $localName string
     * @param $remoteURI string
     */
    public function __construct($localName, $remoteURI)
    {
        $this->localName = $localName;
        $this->remoteURI = $remoteURI;

        $this->storagePath = "hub_sync/{$this->localName}";
    }

    /**
     * @return bool was the update succesful?
     */
    public function update()
    {
        $repositoryPath = "$this->storagePath/repo";
        $absoluteRepositoryPath = storage_path($repositoryPath);

        if (!is_dir($absoluteRepositoryPath)) {
            mkdir($absoluteRepositoryPath, 0777, true);
        }

        if (!file_exists("{$absoluteRepositoryPath}/.git/HEAD")) {
            $cmd = sprintf(
                'git clone -q --recursive --recurse-submodules %s %s',
                $this->remoteURI,
                $absoluteRepositoryPath
            );
        } else {
            $cmd = sprintf('git -C %s pull -q --autostash --rebase', $absoluteRepositoryPath);
        }

        $process = new Process($cmd);

        $process->setTimeout(900);
        $process->start();
        $process->wait();

        return $process->getExitCode() == 0;
    }
}