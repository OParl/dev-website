<?php

namespace OParl\Server\Model;

/**
 * Class System.
 */
class System extends OParl10BaseModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bodies()
    {
        return $this->hasMany(Body::class, 'system_id', 'id');
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
        return route('api.oparl.index');
    }
}
