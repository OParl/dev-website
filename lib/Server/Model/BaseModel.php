<?php

namespace OParl\Server\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * Adjust the table name with the global OParl model prefix.
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function getTable()
    {
        $table = parent::getTable();

        return 'oparl_'.$table;
    }
}
