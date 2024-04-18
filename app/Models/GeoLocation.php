<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    protected $table = 'geolocation';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['station_name', 'country_code', 'island', 'county', 'place', 'hamlet', 'town', 'municipality', 'state_district', 'administrative', 'state', 'village', 'region', 'province', 'city', 'locality', 'postcode', 'country'];

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_name');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }
}
