<?php

namespace App\Http\Controllers\OParl\V10;

use App\Traits\FilterQueryMethods;
use EFrane\Transfugio\Http\APIController;
use EFrane\Transfugio\Http\Method\IndexPaginatedTrait;
use EFrane\Transfugio\Http\Method\ShowItemTrait;

class ConsultationController extends APIController
{
    use IndexPaginatedTrait;
    use ShowItemTrait;
    use FilterQueryMethods;

    public function getCustomQueryParameters()
    {
        return ['created_since', 'created_until', 'modified_since', 'modified_until'];
    }
}
