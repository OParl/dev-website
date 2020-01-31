<?php

namespace App\Jobs;

use App\Exceptions\ValidationFailed;
use App\Mail\ValidationCompleted;
use App\Model\ValidationResult;
use Carbon\Carbon;
use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Mail\Mailer;
use Psr\Log\LoggerInterface;
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

    public function handle(LoggerInterface $log, Filesystem $fs, Mailer $mailer)
    {
        $log->info("Beginning Validation for {$this->endpoint}");

        try {
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
        } catch (ValidationFailed $e) {
            // TODO: proper failure handling including an informative mail
            $log->error("Failed validation for {$this->endpoint}");
        }
    }

    /**
     * @param LoggerInterface $log
     * @param Filesystem      $fs
     *
     * @return array
     */
    protected function runValidator(LoggerInterface $log, Filesystem $fs)
    {
        $validatorRepo = new Repository($fs, 'oparl_validator', '');

        if (!$fs->exists('validation')) {
            $fs->makeDirectory('validation');
        }

        $validationResultFile = storage_path('app/validation/'.uniqid('validation-').'.result');
        $validatorCmd = [
            './validate',
            '--porcelain',
            '-fjson',
            "-o{$validationResultFile}",
            $this->endpoint
        ];

        $validator = new Process($validatorCmd);
        $validator->setTimeout(0);
        $validator->setIdleTimeout(0);
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

        if ($validator->getExitCode() !== 0) {
            throw ValidationFailed::validatorQuitUnexpectedly();
        }

        $result = (array) json_decode(file_get_contents($validationResultFile), true);

        if (file_exists($validationResultFile)) {
            unlink($validationResultFile);
        }

        return $result;
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
