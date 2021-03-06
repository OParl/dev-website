<?php

namespace App\Jobs;

use App\Services\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\Process\Process;

class SpecificationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;
    use InteractsWithRepositoryTrait;
    use Notifiable;

    const AVAILABLE_BUILD_MODES = ['native', 'docker'];

    protected $buildMode = 'docker';

    protected $buildBasename = '';
    protected $buildDir = '';

    public function __construct($treeish = '')
    {
        $this->determineTreeish($treeish, 'oparl.versions.specification.latest');

        $this->buildMode = config('oparl.specificationBuildMode');

        if (!in_array($this->buildMode, self::AVAILABLE_BUILD_MODES)) {
            throw new \InvalidArgumentException("Unsupported build mode {$this->buildMode}");
        }
    }

    public function getBuildMode()
    {
        return $this->buildMode;
    }

    /**
     * @return string slack url
     */
    public function routeNotificationForSlack(): string
    {
        return config('services.slack.ci.endpoint');
    }

    /**
     * @param string $cmd
     * @param array ...$args
     *
     * @return string
     */
    public function prepareCommand($cmd, ...$args): string
    {
        if (count($args) > 0) {
            $cmd = vsprintf($cmd, $args);
        }

        if ($this->buildMode === 'native') {
            return $cmd;
        }

        if ($this->buildMode === 'docker') {
            return sprintf('docker run --rm -v $(pwd):$(pwd) -w $(pwd) oparl/specbuilder:latest %s', $cmd);
        }

        throw new \LogicException("Unsupported build mode: {$this->buildMode}");
    }

    /**
     * @param Filesystem $fs
     *
     * @return Repository
     */
    public function getRepository(Filesystem $fs)
    {
        return new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');
    }

    /**
     * @param Repository $hubSync
     */
    protected function getBuildMeta(Repository $hubSync)
    {
        $process = new Process('python3 build.py --print-basename', storage_path('app/'.$hubSync->getPath()));

        $process->start();
        $process->wait();

        $this->buildBasename = trim($process->getOutput());
        $this->buildDir = sprintf('build/%s', $this->buildBasename);
    }

    public function failed(\Exception $e)
    {
        \Log::debug($e);
    }
}
