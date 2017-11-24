<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ValidationResult extends Model
{
    protected $casts = [
        'result' => 'array',
    ];
}
