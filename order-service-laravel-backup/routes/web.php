<?php

use App\Http\Controllers\OrderWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - OrderService UI
|--------------------------------------------------------------------------
*/

Route::get('/', [OrderWebController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderWebController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderWebController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderWebController::class, 'show'])->name('orders.show');
