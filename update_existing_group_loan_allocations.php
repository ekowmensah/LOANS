<?php

/**
 * Update Existing Group Loan Allocations
 * Calculates and sets allocated_interest and interest_outstanding for existing allocations
 * 
 * Usage: php update_existing_group_loan_allocations.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Client\Entities\GroupMemberLoanAllocation;
use Modules\Loan\Entities\Loan;

echo "🔄 Updating Existing Group Loan Allocations\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get all allocations without interest calculated
$allocations = GroupMemberLoanAllocation::where(function($query) {
    $query->whereNull('allocated_interest')
          ->orWhere('allocated_interest', 0);
})->with('loan')->get();

echo "Found " . $allocations->count() . " allocations to update\n\n";

if ($allocations->count() === 0) {
    echo "✅ No allocations need updating!\n";
    exit(0);
}

$updated = 0;
$failed = 0;
$errors = [];

foreach ($allocations as $allocation) {
    try {
        $loan = $allocation->loan;
        
        if (!$loan) {
            $failed++;
            $errors[] = "Allocation #{$allocation->id}: Loan not found";
            continue;
        }
        
        // Calculate member's share of total interest
        $totalInterest = $loan->interest_derived ?? 0;
        $percentage = $allocation->allocated_percentage ?? 0;
        
        if ($percentage == 0) {
            // Calculate percentage from allocated amount
            $totalPrincipal = $loan->principal ?? 1;
            $percentage = ($allocation->allocated_amount / $totalPrincipal) * 100;
            $allocation->allocated_percentage = $percentage;
        }
        
        $memberInterest = ($totalInterest * $percentage) / 100;
        
        // Update allocation
        $allocation->allocated_interest = $memberInterest;
        $allocation->interest_outstanding = $memberInterest - ($allocation->interest_paid ?? 0);
        $allocation->save();
        
        $updated++;
        
        echo "✅ Allocation #{$allocation->id}\n";
        echo "   Loan: #{$loan->id}\n";
        echo "   Client: {$allocation->client_id}\n";
        echo "   Allocated Amount: " . number_format($allocation->allocated_amount, 2) . "\n";
        echo "   Allocated Interest: " . number_format($memberInterest, 2) . " ({$percentage}%)\n";
        echo "   Interest Outstanding: " . number_format($allocation->interest_outstanding, 2) . "\n";
        echo "   ───────────────────────────────\n";
        
    } catch (\Exception $e) {
        $failed++;
        $errors[] = "Allocation #{$allocation->id}: " . $e->getMessage();
        echo "❌ Failed: Allocation #{$allocation->id} - " . $e->getMessage() . "\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Total Allocations Found: " . $allocations->count() . "\n";
echo "✅ Successfully Updated: {$updated}\n";
echo "❌ Failed: {$failed}\n\n";

if ($failed > 0) {
    echo "Errors:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    echo "\n";
}

if ($updated > 0) {
    echo "✨ Success! {$updated} allocations have been updated with interest calculations.\n\n";
    
    echo "💡 What was updated:\n";
    echo "   - allocated_interest: Member's share of total loan interest\n";
    echo "   - interest_outstanding: Interest remaining after payments\n\n";
    
    echo "🎯 Next Steps:\n";
    echo "   1. Verify the calculations are correct\n";
    echo "   2. Test in Field Agent collection dropdown\n";
    echo "   3. Ensure payment processing updates interest_outstanding\n\n";
}

echo "✅ Update complete!\n\n";
