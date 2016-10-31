<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 13/10/2016
 * Time: 10:50
 */

namespace App\Jobs;

use EFrane\HubSync\Repository;

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
        $hubSync = new Repository('oparl_spec', 'https://github.com/OParl/spec.git');
        $hubSync->update();
    }
}