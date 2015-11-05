<?php namespace OParl\Spec;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;

class LiveVersionUpdater
{
    protected $gitURL = '';
    protected $path = '';
    protected $fs = null;

    public function __construct(Filesystem $fs, $path, $gitURL)
    {
        // make path absolute by assuming it was relative to storage_path('app');
        if (!starts_with($path, DIRECTORY_SEPARATOR)) {
            $path = storage_path("app/{$path}");
        }

        $this->fs = $fs;

        $this->path = $path;
        $this->gitURL = $gitURL;
    }

    /**
     * @return array hash => string(40), last_modified => Carbon
     */
    public function getRepositoryStatus()
    {
        $hash = $this->determineHash();
        $lastModified = $this->determineLastModified();

        return [
            'hash' => $hash,
            'last_modified' => $lastModified
        ];
    }

    public function updateRepository($forceClone = false)
    {
        if ($forceClone || !$this->repositoryExists()) {
            $retVal = $this->cloneRepository();
        } else {
            $retVal = $this->rebaseRepository();
        }

        return $retVal;
    }

    /**
     * Clones the Spec repository
     *
     * @param bool|false $dryRun
     * @return int negative if dry run, zero if everything went fine, greater than zero in any other case
     **/
    public function cloneRepository($dryRun = false)
    {
        $gitCommand = sprintf('git clone --depth=1 %s %s 2>/dev/null', $this->gitURL, $this->path);

        $retVal = -1;

        if (!$dryRun)
        {
            exec($gitCommand, $output, $retVal);
        }

        if (!$dryRun && $retVal == 0) {
            $this->makeLiveVersion();
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
        $gitCommand = sprintf('git pull --rebase origin master 2>/dev/null');

        if (!$this->repositoryExists())
        {
            return -2;
        }

        $retVal = -1;

        if (!$dryRun)
        {
            $oldDir = getcwd();

            chdir($this->path);

            exec($gitCommand, $output, $retVal);

            chdir($oldDir);
        }

        if (!$dryRun && $retVal == 0) {
            $this->makeLiveVersion();
        }

        return $retVal;
    }

    public function repositoryExists()
    {
        return is_dir($this->path);
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

    public function makeLiveVersion($dryRun = false)
    {
        if (!$this->repositoryExists())
        {
            return -2;
        }

        $retVal = -1;

        if (!$dryRun)
        {
            $makeCmd = 'make live';

            $olddir = getcwd();

            chdir($this->path);

            exec($makeCmd, $output, $retVal);

            chdir($olddir);
        }

        return $retVal;
    }

    public function cleanLiveVersion($dryRun = false)
    {
        if (!$this->repositoryExists())
        {
            return -2;
        }

        $retVal = -1;

        if (!$dryRun)
        {
            $makeCmd = 'make clean';

            $olddir = getcwd();

            chdir($this->path);

            exec($makeCmd, $output, $retVal);

            chdir($olddir);
        }

        return $retVal;
    }

    /**
     * @return Carbon|null
     **/
    protected function determineLastModified()
    {
        try {
            $lastModifiedCmd = 'git show HEAD --format="%aD" | head -n1';

            $cwd = getcwd();
            chdir($this->path);

            $rfc2822Date = exec($lastModifiedCmd);
            $rfc2822Date = trim($rfc2822Date);

            chdir($cwd);

            $lastModified = Carbon::createFromFormat(Carbon::RFC2822, $rfc2822Date);
        } catch (\Exception $e) {
            $lastModified = null;
        }

        return $lastModified;
    }

    /**
     * @return string
     **/
    protected function determineHash()
    {
        try {
            $hashCmd = 'git show HEAD --format="%H" | head -n1';

            $cwd = getcwd();
            chdir($this->path);

            $hash = exec($hashCmd);
            $hash = trim($hash);

            chdir($cwd);

        } catch (\ErrorException $e) {
            $hash = '<unknown>';
        }

        return $hash;
    }
}