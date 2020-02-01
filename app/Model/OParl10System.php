<?php

namespace App\Model;

/**
 * Class System.
 */
class OParl10System extends OParl10BaseModel
{
    protected $table = 'systems';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bodies()
    {
        return $this->hasMany(OParl10Body::class, 'system_id', 'id');
    }

    /**
     * @return string
     */
    public function getVendorAttribute()
    {
        return 'http://oparl.org';
    }

    /**
     * @return string
     */
    public function getProductAttribute()
    {
        return 'OParl 1.0 Demoserver @ dev.oparl.org';
    }
}
