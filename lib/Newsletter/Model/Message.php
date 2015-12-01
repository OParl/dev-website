<?php namespace EFrane\Newsletter\Model;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Message extends EloquentModel
{
    protected $dates = ['sent_on'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('newsletter.prefix') . 'messages';

        parent::__construct($attributes);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}