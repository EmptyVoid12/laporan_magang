<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomeComponent;
use App\Livewire\LoginComponent;
use App\Livewire\DashboardComponent;
use App\Livewire\PerangkatComponent;
use App\Livewire\AdminGangguanComponent;
use App\Livewire\GangguanComponent;
use App\Livewire\TeknisiTaskComponent;
use Illuminate\Support\Facades\Auth;

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

// User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/gangguan', GangguanComponent::class)->name('user.gangguan');
});

// Teknisi Routes
Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/teknisi/task', TeknisiTaskComponent::class)->name('teknisi.task');
});
