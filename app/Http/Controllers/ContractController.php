<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Geolocation;
use App\Models\Station;
use App\Models\WeatherData;

class ContractController extends Controller
{
    public function getStationData(Request $request)
    {
        $apiKey = $request->header('api_key');

        $contract = Contract::where('api_key', $apiKey)->first();

        if (!$contract) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $query = Geolocation::query();

        if ($contract->country_code) {
            $query->where('country_code', $contract->country_code);
        }

        $geolocations = $query->get();

        $stations = Station::whereIn('name', $geolocations->pluck('station_name'))
            ->whereBetween('longitude', [$contract->min_longitude, $contract->max_longitude])
            ->whereBetween('latitude', [$contract->min_latitude, $contract->max_latitude])
            ->whereBetween('elevation', [$contract->min_elevation, $contract->max_elevation])
            ->get();

        $weatherData = WeatherData::whereIn('station_name', $stations->pluck('name'))->get();

        return response()->json($weatherData);
    }
}
