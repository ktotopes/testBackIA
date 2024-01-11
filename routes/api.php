<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register',[AuthController::class,'register'])->name('register');
Route::post('login',[AuthController::class,'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum', config('jetstream.auth_session')],function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});
