<?php namespace OParl\Server\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Adjust the table name with the global OParl model prefix
     *
     * @inheritdoc
     * @return string
     */
    public function getTable()
    {
        $table = parent::getTable();

        return 'oparl_' . $table;
    }

}