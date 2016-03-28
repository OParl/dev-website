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
        return 'http://oparl.org'; // TODO: This should lead to the API overview page
    }
}
