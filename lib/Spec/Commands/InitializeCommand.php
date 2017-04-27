<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 04/01/2017
 * Time: 14:39.
 */

namespace OParl\Spec\Commands;

class InitializeCommand extends Command
{
    protected $signature = 'oparl:init';

    public function handle()
    {
        $this->call('oparl:init:schema');

        $this->call('oparl:update:specification');

        collect(config('oparl.downloads.specification'))
            ->map(function ($downloadVersionConstraint) {
                $this->call('oparl:update:downloads', [$downloadVersionConstraint]);
            });
    }
}
