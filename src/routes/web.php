<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomeComponent;
use App\Livewire\LoginComponent;
use App\Livewire\DashboardComponent;
use App\Livewire\PerangkatComponent;
use App\Livewire\AdminGangguanComponent;
use App\Livewire\GangguanComponent;
use App\Livewire\TeknisiTaskComponent;
use App\Livewire\UserTicketDetailComponent;
use App\Http\Controllers\AdminReportExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TechnicianMonthlyHistoryExportController;
use Illuminate\Support\Facades\Auth;
use App\Livewire\AdminPortalComponent;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', HomeComponent::class)->name('home');
Route::get('/login', LoginComponent::class)->name('login')->middleware('guest');
Route::get('/register', \App\Livewire\RegisterComponent::class)->name('register')->middleware('guest');
Route::get('/forgot-password', \App\Livewire\ForgotPasswordComponent::class)->name('password.request')->middleware('guest');
Route::get('/reset-password/{token}', \App\Livewire\ResetPasswordComponent::class)->name('password.reset')->middleware('guest');
Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::guard('web')->logout();
    $request->session()->migrate(true); // Preserve other session data (admin)
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin NOC Mandiri Routes
Route::get('/adminnoc/login', \App\Livewire\AdminNocLoginComponent::class)->name('adminnoc.login');
Route::post('/adminnoc/logout', function (\Illuminate\Http\Request $request) {
    Auth::guard('admin')->logout();
    $request->session()->regenerateToken();
    return redirect()->route('adminnoc.login');
})->name('adminnoc.logout');

Route::middleware(['auth:admin', 'role:admin'])->group(function () {
    Route::get('/adminnoc', \App\Livewire\AdminPortalComponent::class)->name('adminnoc.portal');
});

// User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/gangguan', GangguanComponent::class)->name('user.gangguan');
    Route::get('/user/gangguan/{gangguan}', UserTicketDetailComponent::class)->name('user.gangguan.show');
});

// Teknisi Routes
Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/teknisi/task', TeknisiTaskComponent::class)->name('teknisi.task');
});

// Admin internal routes (public UI, bukan Filament panel)
Route::middleware(['auth:admin,web', 'role:admin'])->group(function () {
    Route::get('/admin-panel/dashboard', DashboardComponent::class)->name('admin.dashboard');
    Route::get('/admin-panel/perangkat', PerangkatComponent::class)->name('admin.perangkat');
    Route::get('/admin-panel/gangguan', AdminGangguanComponent::class)->name('admin.gangguan');
    Route::get('/admin-panel/portal', AdminPortalComponent::class)->name('admin.portal');
});

Route::middleware(['auth:admin,web'])->get('/exports/teknisi/riwayat-bulanan', TechnicianMonthlyHistoryExportController::class)
    ->name('technician.monthly-history.export');

Route::middleware(['auth:admin,web'])->group(function () {
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/admin/reports/export/{type}', AdminReportExportController::class)->name('admin.reports.export');
});
