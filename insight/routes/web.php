<?php

declare(strict_types=1);

use App\Http\Controllers\AuditLiveController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuditLiveController::class, 'create']);
Route::post('/audits', [AuditLiveController::class, 'store'])->middleware('throttle:10,1');
Route::get('/audits/{audit}', [AuditLiveController::class, 'show'])->name('audits.live');
Route::post('/audits/{audit}/start', [AuditLiveController::class, 'start'])->middleware('throttle:20,1')->name('audits.start');
Route::post('/audits/{audit}/retry', [AuditLiveController::class, 'retry'])->middleware('throttle:10,1')->name('audits.retry');
