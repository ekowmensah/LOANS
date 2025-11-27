<?php

/**
 * Test Group Loans Query
 * Tests if group loans are retrieved for a client
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Client\Entities\Client;
use Modules\Loan\Entities\Loan;

echo "🧪 Testing Group Loans Query\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get a client
$client = Client::where('status', 'active')->first();

if (!$client) {
    echo "❌ No active clients found!\n";
    exit(1);
}

echo "✅ Testing with client: {$client->first_name} {$client->last_name} (ID: {$client->id})\n\n";

// Test 1: Individual Loans
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 1: Individual Loans\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$individualLoans = Loan::where('client_id', $client->id)
    ->where('client_type', 'client')
    ->where('status', 'active')
    ->with('loan_product')
    ->get();

echo "Individual Loans: " . $individualLoans->count() . "\n\n";

foreach ($individualLoans as $loan) {
    echo "  💰 Loan #{$loan->id}\n";
    echo "     Product: " . ($loan->loan_product ? $loan->loan_product->name : 'N/A') . "\n";
    echo "     Outstanding: " . number_format($loan->principal_outstanding_derived ?? 0, 2) . "\n";
    echo "     ───────────────────────────────\n";
}

// Test 2: Group Loans
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 2: Group Loans (where client is a member)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    $groupLoans = Loan::where('status', 'active')
        ->where('client_type', 'group')
        ->whereHas('group.members', function($query) use ($client) {
            $query->where('client_id', $client->id);
        })
        ->with(['loan_product', 'group'])
        ->get();

    echo "Group Loans: " . $groupLoans->count() . "\n\n";

    foreach ($groupLoans as $loan) {
        echo "  👥 Loan #{$loan->id} (GROUP)\n";
        echo "     Group: " . ($loan->group ? $loan->group->name : 'N/A') . "\n";
        echo "     Product: " . ($loan->loan_product ? $loan->loan_product->name : 'N/A') . "\n";
        echo "     Outstanding: " . number_format($loan->principal_outstanding_derived ?? 0, 2) . "\n";
        echo "     ───────────────────────────────\n";
    }
} catch (\Exception $e) {
    echo "❌ Error querying group loans:\n";
    echo "   " . $e->getMessage() . "\n\n";
}

// Test 3: Combined (as the controller does)
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 3: Combined Loans (Individual + Group)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$allLoans = $individualLoans->merge($groupLoans ?? collect());

echo "Total Loans: " . $allLoans->count() . "\n";
echo "  - Individual: " . $individualLoans->count() . "\n";
echo "  - Group: " . ($groupLoans->count() ?? 0) . "\n\n";

// Simulate AJAX response
echo "📤 AJAX Response (as would be sent to frontend):\n";
$response = $allLoans->map(function ($loan) {
    $loanType = $loan->client_type === 'group' ? ' (Group)' : '';
    $groupName = $loan->client_type === 'group' && $loan->group ? ' - ' . $loan->group->name : '';
    
    // Calculate total outstanding (principal + interest)
    $principal = $loan->principal_outstanding_derived ?? 0;
    $interest = $loan->interest_outstanding_derived ?? 0;
    $total = $principal + $interest;
    
    // Format balance display
    $balanceDisplay = number_format($principal, 2) . ' + ' . number_format($interest, 2) . ' = ' . number_format($total, 2);
    
    return [
        'id' => $loan->id,
        'name' => ($loan->loan_product ? $loan->loan_product->name : 'Loan') . ' - #' . $loan->id . $groupName . $loanType,
        'balance' => $balanceDisplay,
    ];
});

echo json_encode($response, JSON_PRETTY_PRINT) . "\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($allLoans->count() > 0) {
    echo "✅ SUCCESS! Client has access to " . $allLoans->count() . " loan(s)\n";
    echo "   The dropdown will show both individual and group loans\n\n";
} else {
    echo "⚠️  No active loans found for this client\n";
    echo "   This is normal if the client has no loans\n\n";
}

echo "💡 In the web interface:\n";
echo "   - Individual loans show as: Product Name - #123\n";
echo "   - Group loans show as: Product Name - #123 - Group Name (Group)\n\n";
