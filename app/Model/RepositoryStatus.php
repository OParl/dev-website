<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RepositoryStatus extends Model
{
    protected $fillable = ['name', 'payload'];
    protected $casts = ['payload' => 'json'];
}
