<?php

/**
 * Test AJAX Endpoint for Client Accounts
 * Tests if the get-client-accounts endpoint works
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;
use Modules\Loan\Entities\Loan;

echo "🧪 Testing Client Accounts AJAX Endpoint\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get a client
$client = Client::where('status', 'active')->first();

if (!$client) {
    echo "❌ No active clients found!\n";
    exit(1);
}

echo "✅ Found client: {$client->first_name} {$client->last_name} (ID: {$client->id})\n\n";

// Test 1: Check Savings Accounts
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 1: Savings Accounts\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$savings = Savings::where('client_id', $client->id)
    ->where('status', 'active')
    ->with('savings_product')
    ->get();

echo "Total Savings Accounts: " . $savings->count() . "\n\n";

if ($savings->count() > 0) {
    foreach ($savings as $account) {
        echo "  📊 Account ID: {$account->id}\n";
        echo "     Account Number: {$account->account_number}\n";
        echo "     Product: " . ($account->savings_product ? $account->savings_product->name : 'N/A') . "\n";
        echo "     Balance: " . number_format($account->balance_derived ?? 0, 2) . "\n";
        echo "     Status: {$account->status}\n";
        echo "     ───────────────────────────────\n";
    }
    
    // Simulate AJAX response
    echo "\n📤 AJAX Response (savings_deposit):\n";
    $response = $savings->map(function ($savings) {
        return [
            'id' => $savings->id,
            'name' => ($savings->savings_product ? $savings->savings_product->name : 'Savings') . ' - ' . $savings->account_number,
            'balance' => number_format($savings->balance_derived ?? 0, 2),
        ];
    });
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
} else {
    echo "  ⚠️  No active savings accounts found for this client\n\n";
}

// Test 2: Check Loan Accounts
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 2: Loan Accounts\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$loans = Loan::where('client_id', $client->id)
    ->where('status', 'active')
    ->with('loan_product')
    ->get();

echo "Total Active Loans: " . $loans->count() . "\n\n";

if ($loans->count() > 0) {
    foreach ($loans as $loan) {
        echo "  💰 Loan ID: {$loan->id}\n";
        echo "     Product: " . ($loan->loan_product ? $loan->loan_product->name : 'N/A') . "\n";
        echo "     Principal: " . number_format($loan->principal ?? 0, 2) . "\n";
        echo "     Outstanding: " . number_format($loan->principal_outstanding_derived ?? 0, 2) . "\n";
        echo "     Status: {$loan->status}\n";
        echo "     ───────────────────────────────\n";
    }
    
    // Simulate AJAX response
    echo "\n📤 AJAX Response (loan_repayment):\n";
    $response = $loans->map(function ($loan) {
        return [
            'id' => $loan->id,
            'name' => ($loan->loan_product ? $loan->loan_product->name : 'Loan') . ' - #' . $loan->id,
            'balance' => number_format($loan->principal_outstanding_derived ?? 0, 2),
        ];
    });
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
} else {
    echo "  ⚠️  No active loans found for this client\n\n";
}

// Test 3: Check Route
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 3: Route Check\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$routes = \Illuminate\Support\Facades\Route::getRoutes();
$found = false;

foreach ($routes as $route) {
    if (str_contains($route->uri(), 'field-agent/collection/get-client-accounts')) {
        echo "✅ Route found: " . $route->uri() . "\n";
        echo "   Methods: " . implode(', ', $route->methods()) . "\n";
        echo "   Action: " . $route->getActionName() . "\n";
        $found = true;
        break;
    }
}

if (!$found) {
    echo "❌ Route NOT found!\n";
    echo "   Expected: field-agent/collection/get-client-accounts\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Test Client: {$client->first_name} {$client->last_name} (ID: {$client->id})\n";
echo "Savings Accounts: " . $savings->count() . "\n";
echo "Active Loans: " . $loans->count() . "\n";
echo "Route Status: " . ($found ? "✅ Working" : "❌ Not Found") . "\n\n";

if ($savings->count() === 0 && $loans->count() === 0) {
    echo "⚠️  WARNING: This client has no active accounts or loans!\n";
    echo "   The dropdown will show 'No accounts found'\n";
    echo "   This is normal if the client hasn't opened any accounts yet.\n\n";
    
    echo "💡 To test properly:\n";
    echo "   1. Find a client with active savings or loans\n";
    echo "   2. Or create a savings account for this client\n";
    echo "   3. Then test the collection form\n\n";
}

echo "🌐 Test in browser:\n";
echo "   URL: /field-agent/collection/get-client-accounts?client_id={$client->id}&type=savings_deposit\n";
echo "   Expected: JSON array of savings accounts\n\n";

echo "✨ If you see 'Loading...' that doesn't change:\n";
echo "   1. Open browser console (F12)\n";
echo "   2. Check for JavaScript errors\n";
echo "   3. Check Network tab for AJAX request\n";
echo "   4. Look for error response\n\n";
