<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexItemTrait;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;
use OParl\Server\Model\System;

class SystemController extends APIController
{
    use IndexItemTrait;

    protected $item_id = 1;

    public function show() {
        return $this->index();
    }
}
