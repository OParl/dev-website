<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 13/10/2016
 * Time: 10:50
 */

namespace App\Jobs;


class LiveVersionUpdateJob extends Job
{
    /**
     * @var array
     */
    protected $payload = [];

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {

    }
}