<?php

namespace OParl\Spec\Jobs;

use App\Jobs\Job;
use App\Mail\ValidationCompleted;
use App\Model\ValidationResult;
use Carbon\Carbon;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Mail\Mailer;
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

    public function handle(Log $log, Filesystem $fs, Mailer $mailer)
    {
        $log->info("Beginning Validation for {$this->endpoint}");

        $json = $this->runValidator($log, $fs);

        $this->saveResult($json);

        // TODO: l10n/i18n for validation results
        Carbon::setLocale('de');

        $title = sprintf(
            'OParl Validierung am %s für %s',
            Carbon::now()->format('d.m.Y'),
            $this->endpoint
        );

        $data = [
            'endpoint'           => $this->endpoint,
            'urlEncodedEndpoint' => urlencode($this->endpoint),
            'result'             => $json,
            'validationDate'     => Carbon::now()->format('d.m.Y'),
            'title'              => $title,
        ];

        $log->info("Finished Validation for {$this->endpoint}");

        $mailer->to($this->email)->send(new ValidationCompleted($data));
    }

    /**
     * @param Log        $log
     * @param Filesystem $fs
     *
     * @return array
     */
    protected function runValidator(Log $log, Filesystem $fs)
    {
        $validatorRepo = new Repository($fs, 'oparl_validator', '');

        if (!$fs->exists('validation')) {
            $fs->makeDirectory('validation');
        }

        $validationResultFile = storage_path('app/validation/'.uniqid('validation-').'.json');
        $validatorCmd = sprintf('./validate --porcelain -fjson -o%s "%s"', $validationResultFile, $this->endpoint);

        $validator = new Process($validatorCmd);
        $validator->setEnv([
            'PATH' => "{$validatorRepo->getAbsolutePath()}:/usr/local/bin:/usr/bin",
        ]);
        $validator->setWorkingDirectory($validatorRepo->getAbsolutePath());

        $log->debug("Validator working directory: {$validator->getWorkingDirectory()}");
        $log->debug("Validator command line: {$validator->getCommandLine()}");
        $log->debug('Validator environment', $validator->getEnv());

        $validatorLogPrefix = sprintf('[validator %s] ', substr(sha1($this->endpoint), 0, 6));

        $validator->run(function ($type, $data) use ($log, $validatorLogPrefix) {
            // $type will always be Process::ERR for validator data since it writes it's progress output to STDERR
            $log->debug($validatorLogPrefix.$data);
        });

        $json = (array) json_decode(file_get_contents($validationResultFile), true);
        unlink($validationResultFile);

        return $json;
    }

    /**
     * @param $json
     */
    protected function saveResult($json)
    {
        if ($this->canSaveData) {
            $result = new ValidationResult();
            $result->endpoint = $this->endpoint;
            $result->result = $json;
            $result->save();
        }
    }
}
