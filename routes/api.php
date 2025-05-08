<?php

use App\Http\Controllers\CityHistoryController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TouristAttractionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('hotels', HotelController::class);
Route::apiResource('restaurants', RestaurantController::class);
Route::apiResource('attractions', TouristAttractionController::class);
Route::apiResource('histories', CityHistoryController::class);