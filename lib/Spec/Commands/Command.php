<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 29/12/2016
 * Time: 23:55.
 */

namespace OParl\Spec\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;

class Command extends \Illuminate\Console\Command
{
    use DispatchesJobs;

    public function getTreeishOrDefault($default = 'master')
    {
        $treeish = $this->argument('treeish');

        if (is_null($treeish)) {
            $treeish = $default;
        }

        return $treeish;
    }
}
