<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuditController;
use Illuminate\Support\Facades\Route;

Route::post('/audits', [AuditController::class, 'store'])->middleware('throttle:10,1');
Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('api.audits.show');
Route::get('/audits/{audit}/summary', [AuditController::class, 'summary']);
Route::get('/audits/{audit}/findings', [AuditController::class, 'findings']);
Route::get('/audits/{audit}/pages', [AuditController::class, 'pages']);
