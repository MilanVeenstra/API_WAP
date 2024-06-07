<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Geolocation;
use App\Models\Station;
use App\Models\WeatherData;

class ContractController extends Controller
{
    public function getStationData(Request $request)
    {
        try {
            $contract = $this->getContractFromApiKey($request);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
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


    public function getWarmestStations(Request $request)
    {
        try {
            $contract = $this->getContractFromApiKey($request);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
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

        $weatherData = $stations->map(function ($station) {
            return WeatherData::where('station_name', $station->name)->latest('date')->first();
        })->filter()->sortByDesc('temp')->take(10);

        return response()->json($weatherData->values()->all());
    }

    public function getColdestStations(Request $request)
    {
        try {
            $contract = $this->getContractFromApiKey($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
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

        $weatherData = $stations->map(function ($station) {
            return WeatherData::where('station_name', $station->name)->latest('date')->first();
        })->filter()->sortBy('temp')->take(10);

        return response()->json($weatherData->values()->all());
    }

    public function getStationsWithGeolocation(Request $request)
    {
        try {
            $contract = $this->getContractFromApiKey($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
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
            ->with('geolocation')
            ->get();

        $stations = $stations->map(function ($station) {
            $geolocation = $station->geolocation->makeHidden('id'); // Hide the id
            return array_merge($station->makeHidden('geolocation')->toArray(), $geolocation->toArray());
        });

        return response()->json($stations);
    }

    public function getStationWithFilter(Request $request)
    {
        try {
            $contract = $this->getContractFromApiKey($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        $query = Geolocation::query();
        if ($contract->country_code) {
            $query->where('country_code', $contract->country_code);
        }

        $geolocations = $query->get();

        $stationsQuery = Station::query();
        $stationsQuery->whereIn('name', $geolocations->pluck('station_name'))
            ->whereBetween('longitude', [$contract->min_longitude, $contract->max_longitude])
            ->whereBetween('latitude', [$contract->min_latitude, $contract->max_latitude])
            ->whereBetween('elevation', [$contract->min_elevation, $contract->max_elevation]);

        $filters = ['country_code', 'island', 'county', 'place', 'hamlet', 'town', 'municipality', 'state_district', 'administrative', 'state', 'village', 'region', 'province', 'city', 'locality', 'postcode', 'country'];
        foreach ($filters as $filter) {
            if ($request->has($filter) && $request->input($filter) !== null) {
                $stationsQuery->whereHas('geolocation', function ($query) use ($filter, $request) {
                    $query->where($filter, $request->input($filter));
                });
            }
        }

        $stations = $stationsQuery->get();

        if ($stations->isEmpty()) {
            return response()->json(['message' => 'no stations found!']);
        }

        $stations = $stations->map(function ($station) {
            $latestWeatherData = WeatherData::where('station_name', $station->name)->latest('date')->first();
            return [
                'station_name' => $station->name,
                'longitude' => $station->longitude,
                'latitude' => $station->latitude,
                'elevation' => $station->elevation,
                'temperature' => $latestWeatherData ? $latestWeatherData->temp : null
            ];
        });

        return response()->json($stations);
    }


    private function getContractFromApiKey(Request $request)
    {
        $apiKey = $request->header('api-key');
        $contract = Contract::where('api_key', $apiKey)->first();

        if (!$contract) {
            throw new Exception('Invalid API key');
        }

        return $contract;
    }
}
