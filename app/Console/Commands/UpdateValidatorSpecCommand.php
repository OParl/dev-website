<?php

namespace App\Console\Commands;

use App\Jobs\ValidatorBuildJob;

class UpdateValidatorSpecCommand extends SpecCommand
{
    protected $signature = 'oparl:update:validator {treeish?}';
    protected $description = 'Update the oparl validator';

    public function handle()
    {
        $this->info('Updating validator');

        $treeish = $this->getTreeishOrDefault();

        $this->dispatchNow(new ValidatorBuildJob($treeish));
    }
}
