<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 29/12/2016
 * Time: 23:55
 */

namespace OParl\Spec\Commands;

class Command extends \Illuminate\Console\Command
{
    public function getTreeishOrMaster()
    {
        $treeish = $this->argument('treeish');

        if (is_null($treeish)) {
            $treeish = 'master';
        }

        return $treeish;
    }
}