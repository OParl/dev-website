<?php

namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;

class UpdateDownloadsCommand extends Command
{
    protected $signature = 'oparl:update:downloads {treeish?}';
    protected $description = "Force-update the site's spec downloads";

    public function handle()
    {
        $treeish = $this->getTreeishOrDefault();
        $this->info("Updating downloadables for constraint {$treeish}");

        $this->dispatch(new SpecificationDownloadsBuildJob($treeish));
    }
}
