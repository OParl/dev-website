<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;

class SpecificationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;
    use InteractsWithRepositoryTrait;
    use Notifiable;

    const AVAILABLE_BUILD_MODES = ['native', 'docker'];

    protected $buildMode = 'docker';

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
    public function routeNotificationForSlack()
    {
        return config('services.slack.ci.endpoint');
    }

    /**
     * @param $cmd
     * @param array ...$args
     *
     * @return string
     */
    public function prepareCommand($cmd, ...$args)
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
}
