<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Contract extends Model
{
    protected $table = 'contracts';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['salesperson_id', 'organization_id', 'timestamp', 'country_code', 'administrative_region1', 'administrative_region2', 'longitude', 'latitude', 'elevation', 'api_key'];

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }
}
