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

        if ((strcmp($treeish, 'master') !== 0) && !starts_with($treeish, '~')) {
            $this->error('Constraint must be specified as ~<major>.<minor>');

            return 1;
        }

        return $treeish;
    }
}
