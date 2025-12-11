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

    // Client Assignment Management
    Route::prefix('assignments')->group(function () {
        Route::get('/', [FieldAgentClientAssignmentController::class, 'index'])->name('field_agent.assignments.index');
        Route::get('data', [FieldAgentClientAssignmentController::class, 'get_assignments']);
        Route::get('create', [FieldAgentClientAssignmentController::class, 'create'])->name('field_agent.assignments.create');
        Route::post('store', [FieldAgentClientAssignmentController::class, 'store'])->name('field_agent.assignments.store');
        Route::get('{id}/edit', [FieldAgentClientAssignmentController::class, 'edit'])->name('field_agent.assignments.edit');
        Route::post('{id}/update', [FieldAgentClientAssignmentController::class, 'update'])->name('field_agent.assignments.update');
        Route::get('{id}/deactivate', [FieldAgentClientAssignmentController::class, 'deactivate'])->name('field_agent.assignments.deactivate');
        Route::get('{id}/delete', [FieldAgentClientAssignmentController::class, 'destroy'])->name('field_agent.assignments.destroy');
        
        // API endpoints
        Route::get('agent/{agentId}/clients', [FieldAgentClientAssignmentController::class, 'getAgentClients']);
        Route::post('bulk-assign', [FieldAgentClientAssignmentController::class, 'bulkAssign'])->name('field_agent.assignments.bulk_assign');
    });
});
