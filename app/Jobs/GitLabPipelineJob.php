<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Spec\Jobs\LibOParlUpdateJob;

class GitLabPipelineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    use DispatchesJobs;

    protected $payload = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check wether the payload's last pipeline event is marked as succesful
        // initiate actual update job(s)

        if (!isset($this->payload['object_kind']) || $this->payload['object_kind'] !== 'pipeline') {
            $this->delete();
        }

        $builds = data_get($this->payload, 'builds');

        if (is_array($builds) && count($builds) > 0 && $builds[0]['status'] === 'success') {
            $this->dispatchNow(new LibOParlUpdateJob());
        }
    }
}
