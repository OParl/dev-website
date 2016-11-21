<?php

namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;

class UpdateDownloadablesCommand extends Command
{
    use DispatchesJobs;

    protected $name = 'oparl:update:downloadables';
    protected $description = "Force-update the site's downloads";

    // TODO: allow for only spec or only liboparl

    public function handle()
    {
        $this->info('Updating downloadables');
        $this->dispatch(new SpecificationDownloadsBuildJob());
    }
}