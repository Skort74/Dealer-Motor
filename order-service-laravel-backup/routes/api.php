<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - OrderService
|--------------------------------------------------------------------------
|
| Provider: Menyajikan data riwayat transaksi dan statistik penjualan
| Consumer: Verifikasi stok motor ke MotorService sebelum membuat order
|
*/

// Provider endpoints - statistik harus didefinisikan sebelum {id}
Route::get('/orders/statistics', [OrderController::class, 'statistics']);
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// Consumer + Provider endpoint - membuat order (verifikasi stok ke MotorService)
Route::post('/orders', [OrderController::class, 'store']);
