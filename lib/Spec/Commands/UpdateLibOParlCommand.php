<?php

namespace OParl\Spec\Commands;

use Spec\Jobs\LibOParlBuildJob;

class UpdateLibOParlCommand extends Command
{
    protected $signature = 'oparl:deploy:liboparl';
    protected $description = 'Deploy the latest stable liboparl on the server';

    public function handle()
    {
        $this->info('Updating liboparl');

        $this->dispatchNow(new LibOParlBuildJob());
    }
}
