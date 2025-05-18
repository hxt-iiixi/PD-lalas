<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::resource('products', ProductController::class);
Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
Route::post('/products/{id}/restore', [ProductController::class, 'restore']);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');