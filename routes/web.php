<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('/orders', [AdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'show'])->name('orders.show');
});

Route::any('/{any?}');
