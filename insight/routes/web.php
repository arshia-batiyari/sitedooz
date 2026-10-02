<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AuditAdminController;
use App\Http\Controllers\Admin\RuleAdminController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Web\AuditPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuditPageController::class, 'create'])->name('home');
Route::post('/audits', [AuditPageController::class, 'store'])->name('audits.store');
Route::get('/audits/{audit}', [AuditPageController::class, 'show'])->name('audits.show');

Route::prefix('admin')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])->name('admin.login');
    Route::post('/login', [SessionController::class, 'store'])->name('admin.login.store');

    Route::middleware('insight.admin')->group(function (): void {
        Route::post('/logout', [SessionController::class, 'destroy'])->name('admin.logout');
        Route::get('/audits', [AuditAdminController::class, 'index'])->name('admin.audits.index');
        Route::get('/audits/{audit}', [AuditAdminController::class, 'show'])->name('admin.audits.show');
        Route::post('/audits/{audit}/retry', [AuditAdminController::class, 'retry'])->name('admin.audits.retry');
        Route::get('/rules', [RuleAdminController::class, 'index'])->name('admin.rules.index');
        Route::get('/rules/{rule}/edit', [RuleAdminController::class, 'edit'])->name('admin.rules.edit');
        Route::put('/rules/{rule}', [RuleAdminController::class, 'update'])->name('admin.rules.update');
    });
});
