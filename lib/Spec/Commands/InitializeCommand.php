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
        $this->call('oparl:update:downloads', ['~1.0']);
        $this->call('oparl:update:downloads', ['master']);
    }
}
