<?php

use Illuminate\Support\Facades\Route;
use Modules\FieldAgent\Http\Controllers\FieldAgentController;
use Modules\FieldAgent\Http\Controllers\FieldCollectionController;
use Modules\FieldAgent\Http\Controllers\DailyReportController;
use Modules\FieldAgent\Http\Controllers\FieldAgentClientAssignmentController;

/*
|--------------------------------------------------------------------------
| Field Agent Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('field-agent')->middleware(['auth'])->group(function () {
    
    // Field Agent Dashboard
    Route::get('dashboard', [FieldAgentController::class, 'dashboard']);
    
    // Field Agent - My Clients
    Route::get('my-clients', [FieldAgentController::class, 'my_clients']);
    
    // Field Agent Management
    Route::prefix('agent')->group(function () {
        Route::get('/', [FieldAgentController::class, 'index']);
        Route::get('data', [FieldAgentController::class, 'get_agents']);
        Route::get('create', [FieldAgentController::class, 'create']);
        Route::post('store', [FieldAgentController::class, 'store']);
        Route::get('{id}/show', [FieldAgentController::class, 'show']);
        Route::get('{id}/edit', [FieldAgentController::class, 'edit']);
        Route::post('{id}/update', [FieldAgentController::class, 'update']);
        Route::get('{id}/destroy', [FieldAgentController::class, 'destroy']);
    });

    // Field Collection Management
    Route::prefix('collection')->group(function () {
        Route::get('/', [FieldCollectionController::class, 'index']);
        Route::get('data', [FieldCollectionController::class, 'get_collections']);
        Route::get('create', [FieldCollectionController::class, 'create']);
        Route::post('store', [FieldCollectionController::class, 'store']);
        Route::get('{id}/show', [FieldCollectionController::class, 'show']);
        Route::get('{id}/edit', [FieldCollectionController::class, 'edit']);
        Route::post('{id}/update', [FieldCollectionController::class, 'update']);
        Route::post('search-clients', [FieldCollectionController::class, 'search_clients']);
        Route::get('get-client-accounts', [FieldCollectionController::class, 'get_client_accounts']);
        Route::get('get-loan-payment-info', [FieldCollectionController::class, 'get_loan_payment_info']);
        
        // Verification
        Route::get('verify', [FieldCollectionController::class, 'verify_index']);
        Route::post('bulk-verify', [FieldCollectionController::class, 'bulkVerify']);
        Route::match(['get', 'post'], '{id}/verify', [FieldCollectionController::class, 'verify']);
        Route::post('{id}/reject', [FieldCollectionController::class, 'reject']);
        Route::get('{id}/post', [FieldCollectionController::class, 'post']);
    });

    // Daily Report Management
    Route::prefix('daily-report')->group(function () {
        Route::get('/', [DailyReportController::class, 'index']);
        Route::get('data', [DailyReportController::class, 'get_reports']);
        Route::get('create', [DailyReportController::class, 'create']);
        Route::post('store', [DailyReportController::class, 'store']);
        Route::get('{id}/show', [DailyReportController::class, 'show']);
        Route::post('{id}/submit', [DailyReportController::class, 'submit']);
        Route::get('{id}/approve', [DailyReportController::class, 'approve']);
        Route::post('{id}/reject', [DailyReportController::class, 'reject']);
        Route::post('{id}/record-deposit', [DailyReportController::class, 'record_deposit']);
    });

    // Client-Field Agent Assignment Management (Simple Approach)
    Route::prefix('client-assignments')->group(function () {
        Route::get('/', [\Modules\FieldAgent\Http\Controllers\ClientFieldAgentController::class, 'index'])->name('field_agent.assignments.index');
        Route::get('data', [\Modules\FieldAgent\Http\Controllers\ClientFieldAgentController::class, 'get_clients']);
        Route::post('update', [\Modules\FieldAgent\Http\Controllers\ClientFieldAgentController::class, 'updateAssignment']);
        Route::post('bulk-assign', [\Modules\FieldAgent\Http\Controllers\ClientFieldAgentController::class, 'bulkAssign']);
        Route::get('agent/{agentId}/clients', [\Modules\FieldAgent\Http\Controllers\ClientFieldAgentController::class, 'getAgentClients']);
    });
});
