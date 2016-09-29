<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexItemTrait;

class SystemController extends APIController
{
    use IndexItemTrait;

    protected $item_id = 1;

    public function show()
    {
        return $this->index();
    }
}
