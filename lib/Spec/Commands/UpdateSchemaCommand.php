<?php

namespace OParl\Spec\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationSchemaBuildJob;

class UpdateSchemaCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'oparl:update:schema {constraint?}';
    protected $description = "Force-update the schema assets";

    public function handle()
    {
        $this->info('Updating schema assets');

        $constraint = $this->argument('constraint');
        if (is_null($constraint)) {
            $constraint = 'master';
        }

        if ((strcmp($constraint, 'master') !== 0) && !starts_with($constraint, '~')) {
            $this->error('Constraint must be specified as ~<major>.<minor>');
            return 1;
        }

        $this->dispatch(new SpecificationSchemaBuildJob($constraint));
        return 0;
    }
}