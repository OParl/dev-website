<?php

namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\ValidatorBuildJob;

class UpdateValidatorCommand extends Command
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
