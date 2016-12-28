<?php

namespace OParl\Server\API\Controllers;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;
use EFrane\Transfugio\Query\QueryService;
use EFrane\Transfugio\Query\ValueExpression;

class MeetingController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;

    protected function queryBody(QueryService &$query, ValueExpression $valueExpression)
    {
        $expr = $valueExpression->getExpression();
        $value = $valueExpression->getValue();

        // body is body of organization of any meeting

    }

    protected function queryOrganization(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('organization_id', $valueExpression->getExpression(), $valueExpression->getValue());
    }
}
