<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 04/01/2017
 * Time: 14:39.
 */

namespace OParl\Spec\Commands;

use EFrane\ConsoleAdditions\Command\Batch;

class InitializeCommand extends Command
{
    protected $signature = 'oparl:init';

    /**
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $batch = Batch::create($this->getApplication(), $this->getOutput())
            ->add('oparl:init:schema')
            ->add('oparl:update:specification')
            ->add('oparl:update:validator');

        collect(config('oparl.downloads.specification'))
            ->each(function ($downloadVersionConstraint) use ($batch) {
                $batch->add("oparl:update:downloads {$downloadVersionConstraint}");
            });

        return $batch->run();
    }
}
