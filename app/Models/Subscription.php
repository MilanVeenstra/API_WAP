<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['salesperson_id', 'organization_id', 'timestamp', 'subscription_type', 'station_name', 'api_key'];

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class, 'subscription_type');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_name');
    }
}
