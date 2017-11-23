<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Symfony\Component\Process\Process;

class ValidatorRunJob extends Job
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $canSaveData;

    /**
     * @var Log
     */
    protected $log;

    /**
     * ValidatorRunJob constructor.
     *
     * @param string $endpoint
     * @param string $email
     * @param bool   $canSaveData
     */
    public function __construct($endpoint, $email, $canSaveData)
    {
        $this->endpoint = $endpoint;
        $this->email = $email;
        $this->canSaveData = $canSaveData;
    }

    public function handle(Log $log, Filesystem $fs)
    {
        $this->log = $log;
        $log->info("Beginning Validation for {$this->endpoint}");

        $validatorRepo = new Repository($fs, 'oparl_validator', '');

        $validatorCmd = sprintf('./validate -fjson "%s"', $this->endpoint);

        $validator = new Process($validatorCmd);
        $validator->setEnv([
            'PATH' => "{$validatorRepo->getAbsolutePath()}:/usr/local/bin:/usr/bin",
        ]);
        $validator->setWorkingDirectory($validatorRepo->getAbsolutePath());

        $log->debug("Validator working directory: {$validator->getWorkingDirectory()}");
        $log->debug("Validator command line: {$validator->getCommandLine()}");
        $log->debug('Validator Environment', $validator->getEnv());

        $validator->run([&$this, 'handleProgress']);
    }

    public function handleProgress($type, $data)
    {
        switch ($type) {
            case Process::OUT:
                try {
                    $this->log->info($data);
                } catch (\Exception $e) {
                }
                break;
            case Process::ERR:
                $this->log->error($data);
                break;
        }
    }
}
