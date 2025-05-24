<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AccountsController;
// Redirect root
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Inventory
Route::resource('products', ProductController::class)->middleware('auth');
Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->middleware('auth');
Route::get('/inventory/history', [InventoryController::class, 'history'])->middleware('auth')->name('inventory.history');

// Sales
Route::middleware('auth')->group(function () {
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/sales/update', [SaleController::class, 'update'])->name('sales.update');
    Route::post('/sales/delete', [SaleController::class, 'destroy'])->name('sales.delete');
    Route::post('/sales/reset', [SaleController::class, 'reset'])->name('sales.reset');
    Route::post('/sales/undo', [SaleController::class, 'undo'])->name('sales.undo');
    Route::get('/chart-data/{type}', [DashboardController::class, 'chartData']);
});

// Profile Settings (Custom View)
Route::middleware('auth')->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'editCustom'])->name('profile.custom');
    Route::post('/profile/settings', [ProfileController::class, 'updateCustom'])->name('profile.update.custom');
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

});
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOTP'])->name('password.sendOtp');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOTP'])->name('password.verifyOtp');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');


Route::middleware(['auth', 'is.admin'])->group(function () {
    Route::get('/accounts', [AccountsController::class, 'index'])->name('accounts.index');
    Route::delete('/accounts/{user}', [AccountsController::class, 'destroy'])->name('accounts.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index'); // inventory
});
Route::middleware(['auth', 'is.admin'])->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/accounts', [AccountsController::class, 'index'])->name('accounts.index');
});
// Guest access
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'is.admin'])->group(function () {
    Route::get('/admin/accounts', [AccountsController::class, 'index'])->name('admin.accounts');
    Route::delete('/admin/accounts/{user}', [AccountsController::class, 'destroy'])->name('admin.accounts.delete');
});
Route::post('/admin/accounts/{user}/approve', [AccountsController::class, 'approve'])->name('admin.accounts.approve')->middleware('is.admin');
Route::post('/admin/accounts/{user}/approve', [AccountsController::class, 'approve'])->name('admin.accounts.approve');
Route::delete('/admin/accounts/reject/{user}', [AccountsController::class, 'reject'])->name('admin.accounts.reject');
