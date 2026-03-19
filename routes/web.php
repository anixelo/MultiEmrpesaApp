<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\NoticiaController as SuperAdminNoticiaController;
use App\Http\Controllers\SuperAdmin\CategoriaController as SuperAdminCategoriaController;
use App\Http\Controllers\SuperAdmin\ContactMessageController as SuperAdminContactMessageController;
use App\Http\Controllers\SuperAdmin\SettingsController as SuperAdminSettingsController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use MultiempresaApp\Notifications\Http\Controllers\NotificationController;
use MultiempresaApp\Notas\Http\Controllers\NotaController;
use MultiempresaApp\PlantillasPresupuesto\Http\Controllers\PlantillaPresupuestoController;
use App\Http\Controllers\Worker\DashboardController as WorkerDashboardController;
use MultiempresaApp\Incidents\Http\Controllers\Admin\IncidentController as AdminIncidentController;
use MultiempresaApp\Incidents\Http\Controllers\SuperAdmin\IncidentController as SuperAdminIncidentController;
use MultiempresaApp\Incidents\Http\Controllers\Worker\IncidentController as WorkerIncidentController;
use MultiempresaApp\Plans\Http\Controllers\SuperAdmin\PlanController as SuperAdminPlanController;
use MultiempresaApp\Plans\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use MultiempresaApp\Clientes\Http\Controllers\ClienteController;
use MultiempresaApp\Servicios\Http\Controllers\ServicioController;
use MultiempresaApp\Presupuestos\Http\Controllers\PresupuestoController;
use MultiempresaApp\Presupuestos\Http\Controllers\PresupuestoConfiguracionController;
use MultiempresaApp\Noticias\Http\Controllers\NoticiaController;
use MultiempresaApp\SimpleAnalytics\Http\Controllers\AnalyticsDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Static pages
Route::get('/privacidad', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terminos', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/contacto', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contacto', [PageController::class, 'contactSend'])->name('pages.contact.send');

// Public news detail
Route::get('/noticias/{slug}', [NoticiaController::class, 'show'])->name('noticias.show');
Route::get('/noticias/tag/{slug}', [NoticiaController::class, 'byTag'])->name('noticias.tag');
Route::get('/sitemap.xml', [NoticiaController::class, 'sitemap'])->name('sitemap');

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
Route::get('/presupuestos/p/{token}/pdf', [PresupuestoController::class, 'downloadPublicPdf'])->name('presupuestos.public.pdf');
Route::post('/presupuestos/p/{token}/aceptar', [PresupuestoController::class, 'aceptar'])->name('presupuestos.aceptar');
Route::post('/presupuestos/p/{token}/rechazar', [PresupuestoController::class, 'rechazar'])->name('presupuestos.rechazar');
Route::post('/presupuestos/p/{token}/comentarios', [PresupuestoController::class, 'storePublicComment'])->name('presupuestos.public.comentarios.store');

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

        // Worker incidents
        Route::get('/worker/incidents', [WorkerIncidentController::class, 'index'])->name('worker.incidents.index');
        Route::get('/worker/incidents/create', [WorkerIncidentController::class, 'create'])->name('worker.incidents.create');
        Route::post('/worker/incidents', [WorkerIncidentController::class, 'store'])->name('worker.incidents.store');

        // Clientes, Servicios, Presupuestos, Empresas, Notas - accessible by workers and admins
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
        Route::post('admin/presupuestos/{id}/solicitar-revision', [PresupuestoController::class, 'solicitarRevision'])->name('admin.presupuestos.solicitar-revision');
        Route::post('admin/presupuestos/{id}/validar-revision', [PresupuestoController::class, 'validarRevision'])->name('admin.presupuestos.validar-revision');
        Route::post('admin/presupuestos/{id}/rechazar-revision', [PresupuestoController::class, 'rechazarRevision'])->name('admin.presupuestos.rechazar-revision');
        Route::post('admin/presupuestos/{id}/volver-borrador', [PresupuestoController::class, 'volverBorrador'])->name('admin.presupuestos.volver-borrador');
        Route::post('admin/presupuestos/{id}/comentarios', [PresupuestoController::class, 'storeComment'])->name('admin.presupuestos.comentarios.store');
        Route::get('admin/presupuestos-configuracion', [PresupuestoConfiguracionController::class, 'index'])->name('admin.presupuestos.configuracion');
        Route::post('admin/presupuestos-configuracion', [PresupuestoConfiguracionController::class, 'update'])->name('admin.presupuestos.configuracion.update');

        // Notas
        Route::resource('admin/notas', NotaController::class)->names('admin.notas');
        Route::get('admin/notas/{id}/crear-presupuesto', [NotaController::class, 'crearPresupuesto'])->name('admin.notas.crear-presupuesto');

        // Plantillas de presupuesto
        Route::get('admin/plantillas-presupuesto', [PlantillaPresupuestoController::class, 'index'])->name('admin.plantillas-presupuesto.index');
        Route::get('admin/plantillas-presupuesto/{id}', [PlantillaPresupuestoController::class, 'show'])->name('admin.plantillas-presupuesto.show');
        Route::delete('admin/plantillas-presupuesto/{id}', [PlantillaPresupuestoController::class, 'destroy'])->name('admin.plantillas-presupuesto.destroy');
        Route::post('admin/plantillas-presupuesto/{id}/usar', [PlantillaPresupuestoController::class, 'usar'])->name('admin.plantillas-presupuesto.usar');
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

        // Admin users
        Route::resource('admin/users', AdminUserController::class)->names('admin.users')->except(['show']);
    });

    // Superadmin routes
    Route::middleware(['role:superadministrador'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::resource('users', SuperAdminUserController::class)->except(['show']);
        Route::resource('plans', SuperAdminPlanController::class)->except(['show']);
        Route::resource('noticias', SuperAdminNoticiaController::class)->except(['show']);
        Route::resource('categorias', SuperAdminCategoriaController::class)->except(['show']);
        Route::resource('contact-messages', SuperAdminContactMessageController::class)->only(['index', 'show', 'destroy']);
        Route::post('/companies/{company}/assign-plan', [SuperAdminPlanController::class, 'assignPlan'])->name('companies.assign-plan');
        Route::post('/users/{user}/impersonate', [SuperAdminUserController::class, 'impersonate'])->name('users.impersonate');

        // Superadmin settings
        Route::get('/settings', [SuperAdminSettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [SuperAdminSettingsController::class, 'update'])->name('settings.update');

        // Superadmin incidents
        Route::get('/incidents', [SuperAdminIncidentController::class, 'index'])->name('incidents.index');
        Route::get('/incidents/{incident}', [SuperAdminIncidentController::class, 'show'])->name('incidents.show');
        Route::post('/incidents/{incident}/status', [SuperAdminIncidentController::class, 'updateStatus'])->name('incidents.update-status');
        Route::post('/incidents/{incident}/comment', [SuperAdminIncidentController::class, 'comment'])->name('incidents.comment');
        // Superadmin analytics
        Route::get('/analytics', [AnalyticsDashboardController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/pages', [AnalyticsDashboardController::class, 'pages'])->name('analytics.pages');
        Route::get('/analytics/visits', [AnalyticsDashboardController::class, 'visits'])->name('analytics.visits');
    });
});

require __DIR__ . '/auth.php';

// Public category page (clean slug) — must be last to avoid catching other routes
Route::get('/{slug}', [NoticiaController::class, 'byCategoria'])->name('noticias.categoria')
    ->where('slug', '[a-z0-9][a-z0-9\-]*');
