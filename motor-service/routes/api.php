<?php

use App\Http\Controllers\Api\MotorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - MotorService
|--------------------------------------------------------------------------
|
| Provider: Menyajikan data katalog motor dan stok
| Consumer: Mengambil statistik penjualan dari OrderService
|
*/

// Provider endpoints - diakses oleh OrderService dan client lainnya
Route::get('/motors', [MotorController::class, 'index']);
Route::get('/motors/{id}', [MotorController::class, 'show']);
Route::get('/motors/{id}/stock', [MotorController::class, 'checkStock']);
Route::put('/motors/{id}/stock', [MotorController::class, 'updateStock']);

// Consumer endpoint - mengambil data dari OrderService
Route::post('/motors/update-terlaris', [MotorController::class, 'updateTerlaris']);

// External API — Data motor dari API Ninjas (Public API)
Route::get('/external/motorcycles', [MotorController::class, 'externalMotorcycles']);
Route::get('/external/motorcycles/honda', [MotorController::class, 'externalHonda']);
Route::get('/external/motorcycles/yamaha', [MotorController::class, 'externalYamaha']);
Route::get('/external/motorcycles/kawasaki', [MotorController::class, 'externalKawasaki']);
Route::get('/external/motorcycles/suzuki', [MotorController::class, 'externalSuzuki']);
Route::get('/external/motorcycles/all-brands', [MotorController::class, 'externalAllBrands']);
