<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledBuild extends Model
{
    protected $fillable = ['version', 'email', 'format'];
}
