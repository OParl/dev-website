<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EndpointBody.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="EndpointBody",
 *     required={
 *          "oparlURL",
 *          "name",
 *     },
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="Official name of the body"
 *     ),
 *     @SWG\Property(
 *         property="website",
 *         type="string",
 *         description="The body's website"
 *     )
 * )
 */
class EndpointBody extends Model
{
    protected $fillable = [
        'endpoint_id',
        'oparl_id',
        'name'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'endpoint_id',
        'oparl_id',
    ];

    protected $appends = [
        'oparlURL',
    ];

    protected $casts = [
        'json' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Endpoint
     */
    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class, 'endpoint_id', 'id');
    }

    /**
     * @SWG\Property(
     *   property="oparlURL",
     *   type="string",
     *   description="Link to the OParl entity referencing this body"
     * )
     *
     * @return string
     */
    public function getOParlURLAttribute()
    {
        return $this->oparl_id;
    }
}
