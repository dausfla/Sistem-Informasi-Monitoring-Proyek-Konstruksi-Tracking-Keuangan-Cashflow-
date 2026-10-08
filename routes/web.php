<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\CheckAccessLevel;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('login.demo');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Overview (View access for all authenticated roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects Module
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

    // Projects Mutations (Partial & Full Access)
    Route::middleware([CheckAccessLevel::class . ':partial'])->group(function () {
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::post('/projects/{id}/units', [ProjectController::class, 'storeUnit'])->name('projects.units.store');
    });

    // Projects Deletion (Full Access Only)
    Route::middleware([CheckAccessLevel::class . ':full'])->group(function () {
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    // Financial Cashflows Module
    Route::get('/cashflows', [CashflowController::class, 'index'])->name('cashflows.index');
    Route::middleware([CheckAccessLevel::class . ':full'])->group(function () {
        Route::post('/cashflows', [CashflowController::class, 'store'])->name('cashflows.store');
        Route::delete('/cashflows/{id}', [CashflowController::class, 'destroy'])->name('cashflows.destroy');
    });

    // Document Hub Module
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::middleware([CheckAccessLevel::class . ':partial'])->group(function () {
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    });

    Route::middleware([CheckAccessLevel::class . ':full'])->group(function () {
        Route::patch('/documents/{id}/status', [DocumentController::class, 'updateStatus'])->name('documents.updateStatus');
    });
});
