<?php

namespace OParl\Spec\Commands;

use Spec\Jobs\LibOParlUpdateJob;

class DeployLibOParlCommand extends Command
{
    protected $signature = 'oparl:deploy:liboparl';
    protected $description = 'Deploy the latest stable liboparl on the server';

    public function handle()
    {
        $this->dispatchNow(new LibOParlUpdateJob());
    }
}