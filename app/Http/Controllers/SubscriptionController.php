<?php

namespace App\Http\Controllers;

use App\Models\WeatherData;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController
{
    public function getStationsByApiKey(Request $request)
    {
        $apiKey = $request->header('api_key');

        $subscription = Subscription::where('api_key', $apiKey)->first();

        if (!$subscription) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $station = $subscription->station()->with('geolocation')->first();

        return response()->json($station);
    }

    public function getStationWeatherData(Request $request)
    {
        $apiKey = $request->header('api_key');
        $count = $request->header('count', '10');


        $subscription = Subscription::where('api_key', $apiKey)->first();

        if (!$subscription) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $stationName = $subscription->station_name;

        $query = WeatherData::where('station_name', $stationName)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc');

        if (strtolower($count) !== 'all') {
            $query->limit((int)$count);
        }

        $weatherData = $query->get();

        return response()->json($weatherData);
    }
}
