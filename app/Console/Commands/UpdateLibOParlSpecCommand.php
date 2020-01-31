<?php

namespace App\Console\Commands;

use OParl\Spec\Jobs\LibOParlBuildJob;

class UpdateLibOParlSpecCommand extends SpecCommand
{
    protected $signature = 'oparl:update:liboparl';
    protected $description = 'Update the liboparl build on the server';

    public function handle()
    {
        $this->info('Updating liboparl');

        $this->dispatchNow(new LibOParlBuildJob());
    }
}
