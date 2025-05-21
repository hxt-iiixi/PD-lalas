<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::resource('products', ProductController::class);
Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
Route::post('/products/{id}/restore', [ProductController::class, 'restore']);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
Route::get('/chart-data/{type}', [DashboardController::class, 'chartData']);
Route::post('/sales/update', [SaleController::class, 'update'])->name('sales.update');
Route::post('/sales/delete', [SaleController::class, 'destroy'])->name('sales.delete');
Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');
Route::post('/sales/reset', [SaleController::class, 'reset'])->name('sales.reset');
Route::post('/sales/undo', [SaleController::class, 'undo'])->name('sales.undo');

