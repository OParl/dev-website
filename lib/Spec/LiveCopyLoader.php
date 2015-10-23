<?php namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;
use Parsedown;

class LiveCopyLoader
{
    protected $path = '';
    protected $fs = null;

    public function __construct(Filesystem $fs, $path)
    {
        $this->fs = $fs;
        $this->path = $path;
    }

    /**
     * @return array hash => string(40), last_modified => Carbon
     */
    public function getRepositoryStatus()
    {
        return [
            'hash' => '',
            'last_modified' => null
        ];
    }

    public function updateRepository($forceClone = false)
    {
        if ($forceClone || !$this->repositoryExists())
        {
            $this->cloneRepository();
        } else
        {
            $this->rebaseRepository();
        }
    }

    /**
     * Clones the Spec repository
     *
     * @param bool|false $dryRun
     * @return int negative if dry run, zero if everything went fine, greater than zero in any other case
     **/
    public function cloneRepository($dryRun = false)
    {
        $config = config('services.repositories.spec');

        $gitURL = sprintf('https://github.com/%s/%s.git', $config['user'], $config['repository']);
        $gitCommand = sprintf('git clone --depth=1 %s %s 2>1', $gitURL, $this->path);

        $retVal = -1;

        if (!$dryRun)
        {
            $oldDir = getcwd();

            chdir(storage_path('app'));

            ob_start();
            exec($gitCommand, $output, $retVal);
            ob_end_clean();

            chdir($oldDir);
        }

        return $retVal;
    }

    /**
     * Rebases spec repository to current HEAD
     *
     * @param bool|false $dryRun
     * @return int -1 if dry run, 0 if everything went fine, >0 in any other case, -2 if the repository does not exist
     **/
    public function rebaseRepository($dryRun = false)
    {
        $gitCommand = sprintf('git pull --rebase origin master 2>1');

        if (!$this->repositoryExists())
        {
            return -2;
        }

        $retVal = -1;

        if (!$dryRun)
        {
            $oldDir = getcwd();

            chdir($this->path);

            ob_start();
            exec($gitCommand, $output, $retVal);
            ob_end_clean();

            chdir($oldDir);
        }

        return $retVal;
    }

    public function repositoryExists()
    {
        return is_dir($this->path) || is_dir(storage_path("app/{$this->path}"));
    }

    public function deleteRepository()
    {
        if (!$this->repositoryExists()) {
            return true;
        }

        if (!$this->fs->deleteDirectory($this->path)) {
            // try deleting with the absolute path
            try {
                $dir = new \RecursiveDirectoryIterator($this->path, \FilesystemIterator::SKIP_DOTS);
                $last = $this->path;
            } catch (\UnexpectedValueException $e)
            {
                $dir = new \RecursiveDirectoryIterator(storage_path("app/{$this->path}"), \FilesystemIterator::SKIP_DOTS);
                $last = storage_path("app/{$this->path}");
            }

            $it = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($it as $path) {
                /* @var $path \SplFileInfo */
                if ($path->isFile()) {
                    unlink($path);
                } else if ($path->isDir())
                {
                    rmdir($path);
                }
            }

            rmdir($last);

            if (!is_dir($last)) return true;
        }

        return false;
    }
}