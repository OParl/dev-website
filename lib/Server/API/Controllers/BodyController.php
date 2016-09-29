<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;
use EFrane\Transfugio\Query\QueryService;
use EFrane\Transfugio\Query\ValueExpression;

class BodyController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;

    public function querySystem(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('system_id', $valueExpression->getExpression(), $valueExpression->getValue());
    }
}
