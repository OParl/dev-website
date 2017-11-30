<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Endpoint
 *
 * * @SWG\Definition(
 *     type="object",
 *     definition="Endpoint",
 *     example={
 *         "url": "https://example.com/api",
 *         "title": "Example.com OParl API",
 *         "description": "This is a cool OParl API"
 *     },
 *     required={
 *         "url",
 *         "title"
 *     },
 *     @SWG\Property(
 *         property="url",
 *         type="string",
 *         description="The OParl endpoint's entrypoint"
 *     ),
 *     @SWG\Property(
 *         property="title",
 *         type="string",
 *         description="The OParl endpoint's name"
 *     ),
 *     @SWG\Property(
 *         property="description",
 *         type="string",
 *         description="Optional detailed endpoint description"
 *     )
 * )
 *
 * @package App\Model
 */
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

    /**
     * @SWG\Property( property="bodies", type="array", @SWG\Items( ref="#/definitions/EndpointBody" ))
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bodies()
    {
        return $this->hasMany(EndpointBody::class, 'endpoint_id', 'id');
    }

    /**
     * @SWG\Property(
     *   property="fetched",
     *   type="string",
     *   description="ISO 8601-conform timestamp of when this endpoint information was last updated"
     * )
     *
     * @return string
     */
    public function getFetchedAttribute()
    {
        /** @var Carbon $fetched */
        $fetched = $this->endpoint_fetched;
        if (!is_null($fetched)) {
            return $fetched->toIso8601String();
        }
    }
}
