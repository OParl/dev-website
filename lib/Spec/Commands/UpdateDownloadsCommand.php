<?php

namespace OParl\Spec\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;

class UpdateDownloadsCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'oparl:update:downloads {treeish?}';
    protected $description = "Force-update the site's spec downloads";

    public function handle()
    {
        $this->info('Updating downloadables');

        $treeish = $this->getTreeishOrDefault();

        $this->dispatch(new SpecificationDownloadsBuildJob($treeish));
    }
}