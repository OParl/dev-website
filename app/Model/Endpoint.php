<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Endpoint.
 *
 * * @OA\Schema(
 *     type="object",
 *     schema="Endpoint",
 *     example={
 *         "url": "https://example.com/api",
 *         "title": "Example.com OParl API",
 *         "description": "This is a cool OParl API"
 *     },
 *     required={
 *         "url",
 *         "title"
 *     },
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         description="The OParl endpoint's entrypoint"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The OParl endpoint's name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Optional detailed endpoint description"
 *     )
 * )
 */
class Endpoint extends Model
{
    protected $fillable = [
        'url',
        'title',
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
        'description',
        'endpoint_fetched',
    ];

    protected $appends = [
        'fetched',
        'formattedDescription'
    ];

    /**
     * @OA\Property( property="bodies", type="array", @OA\Items( ref="#/components/schemas/EndpointBody" ))
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bodies()
    {
        return $this->hasMany(EndpointBody::class, 'endpoint_id', 'id');
    }

    /**
     * @OA\Property(
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

        return '';
    }

    public function getFormattedDescriptionAttribute()
    {
        return $this->description;
    }
}
