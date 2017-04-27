<?php

namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;

class UpdateDownloadsCommand extends Command
{
    protected $signature = 'oparl:update:downloads {treeish?}';
    protected $description = "Force-update the site's spec downloads";

    public function handle()
    {
        $this->info('Updating downloadables');

        $treeish = $this->getTreeishOrDefault();

        $this->dispatch(new SpecificationDownloadsBuildJob($treeish));
    }
}
