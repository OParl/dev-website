<?php

namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;

class UpdateDownloadablesCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'oparl:update:downloadables {treeish?}';
    protected $description = "Force-update the site's downloads";

    // TODO: allow for only spec or only liboparl

    public function handle()
    {
        $this->info('Updating downloadables');

        $treeish = $this->argument('treeish');

        if (is_null($treeish)) {
            $treeish = 'master';
        }

        $this->dispatch(new SpecificationDownloadsBuildJob($treeish));
    }
}