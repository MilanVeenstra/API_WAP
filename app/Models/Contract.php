<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Contract extends Model
{
    protected $table = 'contracts';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['salesperson_id', 'organization_id', 'timestamp', 'country_code', 'max_longitude', 'min_longitude', 'max_latitude', 'min_latitude', 'max_elevation', 'min_elevation', 'api_key'];

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
