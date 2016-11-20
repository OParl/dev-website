<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 12:12
 */

namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use OParl\Spec\Jobs\SpecificationBuildJob;

/**
 * Class UpdateSpecificationCommand
 * @package OParl\Spec\Commands
 */
class UpdateSpecificationCommand extends Command
{
    use DispatchesJobs;

    protected $name = 'oparl:update:specification';
    protected $description = "Force-update the specifications' HTML and assets";

    public function handle()
    {
        $this->dispatch(new SpecificationBuildJob([]));
    }
}