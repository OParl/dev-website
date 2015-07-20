<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ScheduledBuild extends Model
{
    protected $fillable = ['version', 'email', 'format'];
}
