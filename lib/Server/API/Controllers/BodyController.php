<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;
use EFrane\Transfugio\Query\QueryService;
use EFrane\Transfugio\Query\ValueExpression;
use OParl\Server\API\FilterQueryMethods;

class BodyController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;
    use FilterQueryMethods;

    public function getCustomQueryParameters()
    {
        return ['created_since', 'created_before', 'modified_since', 'modified_before'];
    }

    public function querySystem(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('system_id', $valueExpression->getExpression(), $valueExpression->getValue());
    }
}
