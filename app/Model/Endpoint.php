<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    protected $fillable = [
        'url',
        'name',
        'description',
    ];

    protected $casts = [
        'system' => 'array',
    ];

    protected $dates = [
        'endpoint_fetched',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'endpoint_fetched',
    ];

    protected $appends = [
        'fetched',
    ];

    public function bodies()
    {
        return $this->hasMany(EndpointBody::class, 'endpoint_id', 'id');
    }

    public function getFetchedAttribute()
    {
        /** @var Carbon $fetched */
        $fetched = $this->endpoint_fetched;
        if (!is_null($fetched)) {
            return $fetched->toIso8601String();
        }
    }
}
