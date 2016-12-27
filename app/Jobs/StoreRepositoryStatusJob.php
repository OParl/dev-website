<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 13/10/2016
 * Time: 10:51
 */

namespace App\Jobs;

use App\Model\RepositoryStatus;

class StoreRepositoryStatusJob extends Job
{
    /**
     * @var string
     */
    protected $repository = '';

    /**
     * @var array
     */
    protected $payload = [];

    public function __construct($repository, array $payload)
    {
        $this->repository = $repository;
        $this->payload = $payload;
    }

    public function handle()
    {
        RepositoryStatus::create([
            'repository' => $this->repository,
            'payload' => $this->payload
        ]);
    }
}
