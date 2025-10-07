<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Development route removed for production
// Route::get('/create-test-user', [AuthController::class, 'createTestUser'])->name('create.test.user');

// Protected Dashboard Routes
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('cctv');
    Route::get('/vms', [DashboardController::class, 'vms'])->name('vms');
});
