<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContractController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/IWA/subscriptions/station-info', [SubscriptionController::class, 'getStationsByApiKey']);
Route::get('/IWA/subscriptions/station-data', [SubscriptionController::class, 'getStationWeatherData']);



Route::get('IWA/contracts/station-data', [ContractController::class, 'getStationData']);
// Pakt de meest recent temp data van elk station en geeft deze terug
Route::get('IWA/contracts/station-data-warmest', [ContractController::class, 'getWarmestStations']);
// Top 10 warmste stations
Route::get('IWA/contracts/station-data-coldest', [ContractController::class, 'getColdestStations']);
// Top 10 koudste stations
Route::get('IWA/contracts/station-list', [ContractController::class, 'getStationsWithGeolocation']);
// Geeft filter variables terug
Route::get('IWA/contracts/station-list-filtered', [ContractController::class, 'getStationWithFilter']);
// filteren op basis van de filter variables
Route::get('IWA/contracts/station-variables', [ContractController::class, 'getFilterVariables']);




