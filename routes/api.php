<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HotelController;
use App\Http\Controllers\API\RoomConfigurationController;

Route::apiResource('hotels', HotelController::class);

// Rutas anidadas para configuraciones de habitaciones de un hotel específico
Route::post('hotels/{hotel}/rooms', [RoomConfigurationController::class, 'store']);
Route::delete('hotels/{hotel}/rooms/{configuration}', [RoomConfigurationController::class, 'destroy']);
