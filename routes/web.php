<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use MultiempresaApp\Notifications\Http\Controllers\NotificationController;
use MultiempresaApp\Tasks\Http\Controllers\Admin\TaskController as AdminTaskController;
use MultiempresaApp\Tasks\Http\Controllers\Worker\DashboardController as WorkerDashboardController;
use MultiempresaApp\Incidents\Http\Controllers\Admin\IncidentController as AdminIncidentController;
use MultiempresaApp\Incidents\Http\Controllers\SuperAdmin\IncidentController as SuperAdminIncidentController;
use MultiempresaApp\Incidents\Http\Controllers\Worker\IncidentController as WorkerIncidentController;
use MultiempresaApp\Plans\Http\Controllers\SuperAdmin\PlanController as SuperAdminPlanController;
use MultiempresaApp\Plans\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use MultiempresaApp\Clientes\Http\Controllers\ClienteController;
use MultiempresaApp\Servicios\Http\Controllers\ServicioController;
use MultiempresaApp\Presupuestos\Http\Controllers\PresupuestoController;
use MultiempresaApp\Presupuestos\Http\Controllers\PresupuestoConfiguracionController;
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

// Public presupuesto routes (no auth required)
Route::get('/presupuestos/p/{token}', [PresupuestoController::class, 'public'])->name('presupuestos.public');
Route::post('/presupuestos/p/{token}/aceptar', [PresupuestoController::class, 'aceptar'])->name('presupuestos.aceptar');
Route::post('/presupuestos/p/{token}/rechazar', [PresupuestoController::class, 'rechazar'])->name('presupuestos.rechazar');

// Authenticated & 2FA verified routes
Route::middleware(['auth', 'two_factor'])->group(function () {

    // Main dashboard router
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stop impersonation (accessible by any authenticated user while impersonating)
    Route::post('/impersonate/stop', [SuperAdminUserController::class, 'stopImpersonating'])->name('impersonate.stop');

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

        // Clientes, Servicios, Presupuestos, Empresas - accessible by workers and admins
        // Empresas
        Route::resource('admin/empresas', EmpresaController::class)->names('admin.empresas')->except(['show']);

        // Clientes
        Route::resource('admin/clientes', ClienteController::class)->names('admin.clientes');
        Route::post('admin/clientes/quick-store', [ClienteController::class, 'quickStore'])->name('admin.clientes.quick-store');

        // Servicios
        Route::resource('admin/servicios', ServicioController::class)->names('admin.servicios');

        // Presupuestos
        Route::resource('admin/presupuestos', PresupuestoController::class)->names('admin.presupuestos')->except(['show']);
        Route::get('admin/presupuestos/{id}', [PresupuestoController::class, 'show'])->name('admin.presupuestos.show');
        Route::post('admin/presupuestos/{id}/enviar', [PresupuestoController::class, 'enviar'])->name('admin.presupuestos.enviar');
        Route::post('admin/presupuestos/{id}/duplicar', [PresupuestoController::class, 'duplicar'])->name('admin.presupuestos.duplicar');
        Route::get('admin/presupuestos/{id}/pdf', [PresupuestoController::class, 'downloadPdf'])->name('admin.presupuestos.pdf');
        Route::post('admin/presupuestos/{id}/send-email', [PresupuestoController::class, 'sendEmail'])->name('admin.presupuestos.send-email');
        Route::get('admin/presupuestos-configuracion', [PresupuestoConfiguracionController::class, 'index'])->name('admin.presupuestos.configuracion');
        Route::post('admin/presupuestos-configuracion', [PresupuestoConfiguracionController::class, 'update'])->name('admin.presupuestos.configuracion.update');
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
        Route::post('/users/{user}/impersonate', [SuperAdminUserController::class, 'impersonate'])->name('users.impersonate');

        // Superadmin incidents
        Route::get('/incidents', [SuperAdminIncidentController::class, 'index'])->name('incidents.index');
        Route::get('/incidents/{incident}', [SuperAdminIncidentController::class, 'show'])->name('incidents.show');
        Route::post('/incidents/{incident}/status', [SuperAdminIncidentController::class, 'updateStatus'])->name('incidents.update-status');
        Route::post('/incidents/{incident}/comment', [SuperAdminIncidentController::class, 'comment'])->name('incidents.comment');
    });
});

require __DIR__ . '/auth.php';
