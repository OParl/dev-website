<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EndpointBody extends Model
{
    protected $fillable = [
        'oparl_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'endpoint_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class, 'endpoint_id', 'id');
    }
}
