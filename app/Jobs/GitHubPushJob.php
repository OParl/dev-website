<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 07/10/2016
 * Time: 18:52
 */

namespace App\Jobs;


class GitHubPushJob extends Job
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }
}