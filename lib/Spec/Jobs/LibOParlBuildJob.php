<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Psr\Log\LoggerInterface;

class LibOParlBuildJob extends Job implements ShouldQueue
{
    use InteractsWithRepositoryTrait;
    use Notifiable;

    protected $prefix = '';

    public function __construct($treeish = '')
    {
        $this->prefix = config('oparl.liboparl.prefix');
    }

    public function handle(Filesystem $fs, LoggerInterface $log)
    {
        $repo = new Repository($fs, 'oparl_liboparl', 'https://github.com/OParl/liboparl.git');
        $repo = $this->getUpdatedHubSync($repo, $log);

        $buildDir = $repo->getAbsolutePath().'/build';

        if (!is_dir($buildDir)) {
            mkdir($buildDir);
        }

        $meson = "meson -Dbuild_valadoc=false -Dbuild_test=false --prefix={$this->prefix}";
        if (!file_exists($buildDir.'/build.ninja')) {
            $success = $this->runSynchronousCommand($buildDir, $meson, $output);

            if (!$success) {
                $log->error($output);
            }
        }

        $ninja = 'ninja install';
        $success = $this->runSynchronousCommand($buildDir, $ninja, $output);
        if (!$success) {
            $log->error($output);
        }
    }
}
