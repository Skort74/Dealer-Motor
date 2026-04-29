<?php

use App\Http\Controllers\Api\GatewayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Gateway Routes
|--------------------------------------------------------------------------
*/

// Health Check
Route::get('/health', [GatewayController::class, 'health']);

// Motor Service Proxy (data lokal dealer)
Route::get('/motors', [GatewayController::class, 'getMotors']);
Route::get('/motors/honda', [GatewayController::class, 'getMotorsHonda']);
Route::get('/motors/yamaha', [GatewayController::class, 'getMotorsYamaha']);
Route::get('/motors/{id}', [GatewayController::class, 'getMotor']);
Route::get('/motors/{id}/stock', [GatewayController::class, 'getMotorStock']);
Route::put('/motors/{id}/stock', [GatewayController::class, 'updateMotorStock']);
Route::post('/motors/update-terlaris', [GatewayController::class, 'updateTerlaris']);

// Order Service Proxy
Route::get('/orders/statistics', [GatewayController::class, 'getStatistics']);
Route::get('/orders', [GatewayController::class, 'getOrders']);
Route::get('/orders/{id}', [GatewayController::class, 'getOrder']);
Route::post('/orders', [GatewayController::class, 'createOrder']);

// External API — API Ninjas Motorcycles (via Gateway → MotorService → API Ninjas)
Route::get('/external/motorcycles', [GatewayController::class, 'externalMotorcycles']);
Route::get('/external/motorcycles/honda', [GatewayController::class, 'externalHonda']);
Route::get('/external/motorcycles/yamaha', [GatewayController::class, 'externalYamaha']);
Route::get('/external/motorcycles/kawasaki', [GatewayController::class, 'externalKawasaki']);
Route::get('/external/motorcycles/suzuki', [GatewayController::class, 'externalSuzuki']);
Route::get('/external/motorcycles/all-brands', [GatewayController::class, 'externalAllBrands']);
