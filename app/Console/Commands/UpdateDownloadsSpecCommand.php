<?php

namespace App\Console\Commands;

use OParl\Spec\Jobs\SpecificationDownloadsBuildJob;

class UpdateDownloadsSpecCommand extends SpecCommand
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
