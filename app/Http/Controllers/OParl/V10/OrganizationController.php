<?php

namespace App\Http\Controllers\OParl\V10;

use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;
use EFrane\Transfugio\Query\QueryService;
use EFrane\Transfugio\Query\ValueExpression;
use OParl\Server\API\FilterQueryMethods;

class OrganizationController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;
    use FilterQueryMethods;

    public function getCustomQueryParameters()
    {
        return ['created_since', 'created_until', 'modified_since', 'modified_until'];
    }

    protected function queryBody(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('body_id', $valueExpression->getExpression(), $valueExpression->getValue());
    }
}
