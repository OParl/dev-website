<?php namespace EFrane\Newsletter\Model;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Subscriber extends EloquentModel
{
    protected $fillable = ['name', 'email', 'company'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('newsletter.prefix') . 'subscribers';

        parent::__construct($attributes);
    }

    public function subscriptions() {
        return $this->belongsToMany(Subscription::class, 'subscriber_subscription', 'subscription_id', 'subscriber_id');
    }
}