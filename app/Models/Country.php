<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'country_code';
    protected $fillable = ['country_code', 'country'];

    public function nearestLocations()
    {
        return $this->hasMany(NearestLocation::class, 'country_code');
    }

    public function geolocations()
    {
        return $this->hasMany(Geolocation::class, 'country_code');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'country_code');
    }
}
