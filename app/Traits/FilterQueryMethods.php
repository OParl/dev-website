<?php

namespace App\Traits;

use EFrane\Transfugio\Query\QueryService;
use EFrane\Transfugio\Query\ValueExpression;

/**
 * Provide the methods for OParl Filter Queries.
 *
 * Queries for lists can be filtered by the following parameters:
 *
 * - created_since
 * - created_before
 * - modified_since
 * - modified_before
 *
 * Which all expect ISO 8801 Timestamps. These are nicely transformed into Carbon
 * instances by Transfugio which makes all this querying a matter of applying the
 * correct operator on the correct model field.
 */
trait FilterQueryMethods
{
    public function queryCreatedSince(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('created_at', '>=', $valueExpression->getValue());
    }

    public function queryCreatedUntil(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('created_at', '<=', $valueExpression->getValue());
    }

    public function queryModifiedSince(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('updated_at', '>=', $valueExpression->getValue());
    }

    public function queryModifiedUntil(QueryService &$query, ValueExpression $valueExpression)
    {
        $query->where('updated_at', '<=', $valueExpression->getValue());
    }
}
