<?php

namespace OParl\Spec\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationSchemaBuildJob;

class UpdateSchemaCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'oparl:update:schema {treeish?}';
    protected $description = "Force-update the schema assets";

    public function handle()
    {
        $this->info('Updating schema assets');

        $treeish = $this->getTreeishOrMaster();

        $this->dispatch(new SpecificationSchemaBuildJob($treeish));
    }
}