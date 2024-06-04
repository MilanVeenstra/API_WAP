<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContractController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/IWA/subscriptions/station-info', [SubscriptionController::class, 'getStationsByApiKey']);
Route::get('/IWA/subscriptions/station-data', [SubscriptionController::class, 'getStationWeatherData']);
Route::get('IWA/contracts/station-data', [ContractController::class, 'getStationData']);
Route::get('IWA/contracts/station-data-warmest', [ContractController::class, 'getWarmestStations']);
Route::get('IWA/contracts/station-data-coldest', [ContractController::class, 'getColdestStations']);


