<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'station';
    public $timestamps = false;
    protected $primaryKey = 'name';
    protected $fillable = ['name', 'longitude', 'latitude', 'elevation'];

    public function geolocation()
    {
        return $this->hasOne(Geolocation::class, 'station_name', 'name');
    }

    public function nearestLocations()
    {
        return $this->hasMany(NearestLocation::class, 'station_name');
    }

    public function weatherData()
    {
        return $this->hasMany(WeatherData::class, 'station_name');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'station_name');
    }
}
