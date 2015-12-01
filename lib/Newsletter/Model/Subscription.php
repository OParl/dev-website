<?php namespace EFrane\Newsletter\Model;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Subscription extends EloquentModel
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('newsletter.prefix') . 'subscriptions';

        parent::__construct($attributes);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}