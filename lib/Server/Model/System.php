<?php

namespace OParl\Server\Model;

class System extends BaseModel
{
    public function bodies()
    {
        return $this->hasMany(Body::class, 'system_id', 'id');
    }

    public function getVendorAttribute()
    {
        return 'http://oparl.org';
    }

    public function getProductAttribute()
    {
        return route('api.index');
    }
}
