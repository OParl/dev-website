<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 23/11/2016
 * Time: 11:20
 */

namespace OParl\Spec\Repositories;

use EFrane\HubSync\Repository;

class SpecificationDownloadRepository extends DownloadRepository
{
    protected function getIdentifier()
    {
        return 'specification';
    }

    public function getVersion($version)
    {
        $hubSync = new Repository($this->fs, 'oparl_spec', 'https://github.com/OParl/spec.git');
        return parent::getVersion($hubSync->getUniqueRevision($version));
    }
}