<?php

use App\Http\Controllers\GatewayWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Gateway — Web Routes (Unified Dashboard)
|--------------------------------------------------------------------------
*/

Route::get('/', [GatewayWebController::class, 'index'])->name('gateway.index');

// Motor routes (via gateway)
Route::get('/motors', [GatewayWebController::class, 'motors'])->name('gateway.motors');
Route::get('/motors/{id}', [GatewayWebController::class, 'motorDetail'])->name('gateway.motor.detail');
Route::post('/sync-terlaris', [GatewayWebController::class, 'syncTerlaris'])->name('gateway.syncTerlaris');

// Order routes (via gateway)
Route::get('/orders', [GatewayWebController::class, 'orders'])->name('gateway.orders');
Route::get('/orders/create', [GatewayWebController::class, 'orderCreate'])->name('gateway.order.create');
Route::post('/orders', [GatewayWebController::class, 'orderStore'])->name('gateway.order.store');
Route::get('/orders/{id}/edit', [GatewayWebController::class, 'orderEdit'])->name('gateway.order.edit');
Route::put('/orders/{id}', [GatewayWebController::class, 'orderUpdate'])->name('gateway.order.update');
Route::delete('/orders/{id}', [GatewayWebController::class, 'orderCancel'])->name('gateway.order.cancel');
Route::get('/orders/{id}', [GatewayWebController::class, 'orderDetail'])->name('gateway.order.detail');

// External API — Data motor dari API Ninjas
Route::get('/external-api', [GatewayWebController::class, 'externalApi'])->name('gateway.external');
