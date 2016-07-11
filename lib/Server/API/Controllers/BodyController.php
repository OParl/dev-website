<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;
use OParl\Server\Model\Body;

class BodyController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;
}
