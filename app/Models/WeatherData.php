<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    protected $table = 'weather_data';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['station_name', 'date', 'time', 'temp', 'dewp', 'stp', 'slp', 'visib', 'wdsp', 'prcp', 'sndp', 'frshtt', 'cldc', 'wnddir'];


    public function station()
    {
        return $this->belongsTo(Station::class, 'station_name');
    }
}
