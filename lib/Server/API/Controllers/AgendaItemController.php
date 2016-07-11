<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;

class AgendaItemController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;
}
