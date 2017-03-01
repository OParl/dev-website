<?php

namespace OParl\Spec\Commands;

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

        $treeish = $this->getTreeishOrDefault();

        $this->dispatch(new SpecificationDownloadsBuildJob($treeish));
    }
}