<?php

namespace OParl\Server\Model;

use App\Exceptions\ConnectionNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel.
 */
class OParl10BaseModel extends Model
{
    use SoftDeletes;

    protected static $modelConfiguration;

    /**
     * BaseModel constructor.
     *
     * {@inheritdoc}
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!is_array(self::$modelConfiguration)) {
            self::setModelConfiguration(config('api.default'));
        }

        $connection = config('database.'.self::$modelConfiguration['connection']);

        if (strlen($connection) == 0) {
            throw new ConnectionNotFoundException();
        }

        $this->setConnection($connection);
    }

    public static function getModelConfiguration()
    {
        return self::$modelConfiguration;
    }

    public static function setModelConfiguration($key)
    {
        self::$modelConfiguration = config('api.'.$key);
    }

    /**
     * Adjust the table name with the current model prefix.
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function getTable()
    {
        $table = parent::getTable();

        return self::$modelConfiguration['prefix'].$table;
    }

    public function getModelName()
    {
        return strtolower(class_basename($this));
    }
}
