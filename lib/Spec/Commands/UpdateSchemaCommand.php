<?php

namespace OParl\Spec\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationSchemaBuildJob;

class UpdateSchemaCommand extends Command
{
    protected $signature = 'oparl:update:schema {treeish?}';
    protected $description = 'Force-update the schema assets';

    public function handle()
    {
        $constraint = $this->getTreeishOrDefault('master');

        $this->info("Updating schema assets for constraint '{$constraint}'");

        $this->dispatch(new SpecificationSchemaBuildJob($constraint));

        return 0;
    }
}
