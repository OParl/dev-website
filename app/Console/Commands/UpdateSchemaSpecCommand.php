<?php

namespace App\Console\Commands;

use App\Jobs\SpecificationSchemaBuildJob;

class UpdateSchemaSpecCommand extends SpecCommand
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
