<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/IWA/subscriptions/station-info', [SubscriptionController::class, 'getStationsByApiKey']);
Route::get('/IWA/subscriptions/station-data', [SubscriptionController::class, 'getStationWeatherData']);
