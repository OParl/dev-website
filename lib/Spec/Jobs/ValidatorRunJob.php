<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
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

    public function handle(Log $log)
    {
        $this->log = $log;

        $validatorCmd = sprintf('./validate -fjson %s %s');
        $validator = new Process($validatorCmd);
        $validator->start();

        foreach ($validator as $type => $data) {
            $this->handleProgress($type, $data);
        }
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
                break;
        }
    }
}
