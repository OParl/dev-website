<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 23/11/2016
 * Time: 11:20
 */

namespace OParl\Spec\Repositories;

class SpecificationDownloadRepository extends DownloadRepository
{
    protected function getIdentifier()
    {
        return 'specification';
    }
}