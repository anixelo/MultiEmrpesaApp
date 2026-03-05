<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\PlanController as SuperAdminPlanController;
use App\Http\Controllers\SuperAdmin\IncidentController as SuperAdminIncidentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\IncidentController as AdminIncidentController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Worker\DashboardController as WorkerDashboardController;
use App\Http\Controllers\Worker\IncidentController as WorkerIncidentController;
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

    // Notifications (all authenticated users)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Shared incident detail (accessible by all roles)
    Route::get('/incidents/{incident}', [WorkerIncidentController::class, 'show'])->name('incidents.show');
    Route::post('/incidents/{incident}/comment', [WorkerIncidentController::class, 'comment'])->name('incidents.comment');

    // Worker routes
    Route::middleware(['role:trabajador|administrador|superadministrador'])->group(function () {
        Route::get('/worker/dashboard', [WorkerDashboardController::class, 'index'])->name('worker.dashboard');
        Route::patch('/worker/tasks/{task}/status', [WorkerDashboardController::class, 'updateTaskStatus'])->name('worker.tasks.update-status');

        // Worker incidents
        Route::get('/worker/incidents', [WorkerIncidentController::class, 'index'])->name('worker.incidents.index');
        Route::get('/worker/incidents/create', [WorkerIncidentController::class, 'create'])->name('worker.incidents.create');
        Route::post('/worker/incidents', [WorkerIncidentController::class, 'store'])->name('worker.incidents.store');
    });

    // Admin routes
    Route::middleware(['role:administrador|superadministrador'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Admin subscription
        Route::get('/admin/subscription', [AdminSubscriptionController::class, 'index'])->name('admin.subscription');
        Route::post('/admin/subscription/change-plan', [AdminSubscriptionController::class, 'changePlan'])->name('admin.subscription.change-plan');

        // Admin incidents
        Route::get('/admin/incidents', [AdminIncidentController::class, 'index'])->name('admin.incidents.index');
        Route::get('/admin/incidents/{incident}', [AdminIncidentController::class, 'show'])->name('admin.incidents.show');

        // Admin tasks
        Route::resource('admin/tasks', AdminTaskController::class)->names('admin.tasks')->except(['show']);

        // Admin users
        Route::resource('admin/users', AdminUserController::class)->names('admin.users')->except(['show']);
    });

    // Superadmin routes
    Route::middleware(['role:superadministrador'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::resource('users', SuperAdminUserController::class)->except(['show']);
        Route::resource('plans', SuperAdminPlanController::class)->except(['show']);
        Route::post('/companies/{company}/assign-plan', [SuperAdminPlanController::class, 'assignPlan'])->name('companies.assign-plan');

        // Superadmin incidents
        Route::get('/incidents', [SuperAdminIncidentController::class, 'index'])->name('incidents.index');
        Route::get('/incidents/{incident}', [SuperAdminIncidentController::class, 'show'])->name('incidents.show');
        Route::post('/incidents/{incident}/status', [SuperAdminIncidentController::class, 'updateStatus'])->name('incidents.update-status');
        Route::post('/incidents/{incident}/comment', [SuperAdminIncidentController::class, 'comment'])->name('incidents.comment');
    });
});

require __DIR__ . '/auth.php';
