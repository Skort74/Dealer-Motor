<?php

use App\Http\Controllers\MotorWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - MotorService UI
|--------------------------------------------------------------------------
*/

Route::get('/', [MotorWebController::class, 'index'])->name('motors.index');
Route::get('/motors/{id}', [MotorWebController::class, 'show'])->name('motors.show');
Route::post('/sync-terlaris', [MotorWebController::class, 'syncTerlaris'])->name('motors.syncTerlaris');
