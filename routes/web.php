<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Worker\DashboardController as WorkerDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Google OAuth
Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// 2FA Challenge (accessible when logged in but not 2FA verified)
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor/challenge',  [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('/two-factor/verify',    [TwoFactorController::class, 'verify'])->name('two-factor.verify');
});

// Authenticated & 2FA verified routes
Route::middleware(['auth', 'two_factor'])->group(function () {

    // Main dashboard router
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA setup
    Route::get('/two-factor/setup',   [TwoFactorController::class, 'setup'])->name('two-factor.setup');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor/disable',[TwoFactorController::class, 'disable'])->name('two-factor.disable');

    // Worker dashboard
    Route::middleware(['role:trabajador|administrador|superadministrador'])->group(function () {
        Route::get('/worker/dashboard', [WorkerDashboardController::class, 'index'])->name('worker.dashboard');
        Route::patch('/worker/tasks/{task}/status', [WorkerDashboardController::class, 'updateTaskStatus'])->name('worker.tasks.update-status');
    });

    // Admin dashboard
    Route::middleware(['role:administrador|superadministrador'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Superadmin routes
    Route::middleware(['role:superadministrador'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::resource('users',     SuperAdminUserController::class)->except(['show']);
    });
});

require __DIR__ . '/auth.php';
