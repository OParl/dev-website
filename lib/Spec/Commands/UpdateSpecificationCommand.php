<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 12:12.
 */

namespace OParl\Spec\Commands;

use OParl\Spec\Jobs\SpecificationLiveVersionBuildJob;

/**
 * Class UpdateSpecificationCommand.
 */
class UpdateSpecificationCommand extends Command
{
    protected $signature = 'oparl:update:specification {treeish?}';
    protected $description = "Force-update the specifications' HTML and assets";

    public function handle()
    {
        try {
            $treeish = $this->getTreeishOrDefault(config('oparl.versions.specification.master'));
        } catch (\InvalidArgumentException $e) {
            return 1;
        }

        $this->info('Updating specification for constraint '.$treeish);

        return $this->dispatch(new SpecificationLiveVersionBuildJob($treeish));
    }
}
