<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class SubscriptionType extends Model
{
    protected $table = 'subscription_type';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['subscription_name'];

    public function subscription()
    {
        return $this->hasMany(Subscription::class, 'subscription_type');
    }
}
