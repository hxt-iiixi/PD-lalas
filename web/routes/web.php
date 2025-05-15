<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
