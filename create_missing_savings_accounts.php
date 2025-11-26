<?php
/**
 * Create Savings Accounts for Existing Clients
 * This script creates savings accounts for all clients who don't have one
 * 
 * Usage: php create_missing_savings_accounts.php
 */

require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Client\Entities\Client;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsProduct;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Auth;

echo "===========================================\n";
echo "Create Missing Savings Accounts\n";
echo "===========================================\n\n";

// Get default savings product
$defaultProductId = Setting::where('setting_key', 'default_savings_product_id')->first();
if (!$defaultProductId || empty($defaultProductId->setting_value)) {
    echo "✗ Error: Default savings product not configured.\n";
    echo "Please set the default savings product first.\n";
    exit(1);
}

$savingsProduct = SavingsProduct::find($defaultProductId->setting_value);
if (!$savingsProduct) {
    echo "✗ Error: Default savings product (ID: {$defaultProductId->setting_value}) not found.\n";
    exit(1);
}

echo "Using Savings Product: {$savingsProduct->name}\n\n";

// Get all clients without active savings accounts
$clientsWithoutSavings = Client::whereDoesntHave('savings', function($query) {
    $query->where('status', 'active');
})->get();

if ($clientsWithoutSavings->isEmpty()) {
    echo "✓ All clients already have savings accounts!\n";
    echo "No action needed.\n";
    exit(0);
}

echo "Found {$clientsWithoutSavings->count()} clients without savings accounts.\n";
echo "Creating savings accounts...\n\n";

$created = 0;
$failed = 0;
$errors = [];

// Set a default user ID for created_by (use first admin user)
$adminUser = \Modules\User\Entities\User::whereHas('roles', function($query) {
    $query->where('name', 'admin');
})->first();

if (!$adminUser) {
    echo "✗ Error: No admin user found. Cannot proceed.\n";
    exit(1);
}

// Temporarily set auth user
Auth::loginUsingId($adminUser->id);

foreach ($clientsWithoutSavings as $client) {
    try {
        // Create savings account
        $savings = new Savings();
        $savings->currency_id = $savingsProduct->currency_id;
        $savings->created_by_id = $adminUser->id;
        $savings->client_id = $client->id;
        $savings->savings_product_id = $savingsProduct->id;
        $savings->savings_officer_id = $client->loan_officer_id;
        $savings->branch_id = $client->branch_id;
        $savings->interest_rate = $savingsProduct->default_interest_rate;
        $savings->interest_rate_type = $savingsProduct->interest_rate_type;
        $savings->compounding_period = $savingsProduct->compounding_period;
        $savings->interest_posting_period_type = $savingsProduct->interest_posting_period_type;
        $savings->decimals = $savingsProduct->decimals;
        $savings->interest_calculation_type = $savingsProduct->interest_calculation_type;
        $savings->automatic_opening_balance = 0;
        $savings->lockin_period = $savingsProduct->lockin_period ?? 0;
        $savings->lockin_type = $savingsProduct->lockin_type ?? 'days';
        $savings->allow_overdraft = $savingsProduct->allow_overdraft;
        $savings->overdraft_limit = $savingsProduct->overdraft_limit;
        $savings->overdraft_interest_rate = $savingsProduct->overdraft_interest_rate;
        $savings->minimum_overdraft_for_interest = $savingsProduct->minimum_overdraft_for_interest;
        $savings->submitted_on_date = $client->created_date ?? date('Y-m-d');
        $savings->submitted_by_user_id = $adminUser->id;
        $savings->status = 'active';
        $savings->approved_on_date = date('Y-m-d');
        $savings->approved_by_user_id = $adminUser->id;
        $savings->activated_on_date = date('Y-m-d');
        $savings->activated_by_user_id = $adminUser->id;
        $savings->save();

        // Generate account number
        $savings->account_number = generate_savings_reference('savings.reference_prefix', $savings);
        $savings->save();

        activity()->on($savings)
            ->withProperties(['id' => $savings->id, 'bulk_created' => true])
            ->log('Bulk Created Savings Account');

        echo "✓ Created savings account for: {$client->first_name} {$client->last_name} (Account: {$savings->account_number})\n";
        $created++;

    } catch (\Exception $e) {
        echo "✗ Failed for: {$client->first_name} {$client->last_name} - {$e->getMessage()}\n";
        $failed++;
        $errors[] = [
            'client' => $client->first_name . ' ' . $client->last_name,
            'error' => $e->getMessage()
        ];
    }
}

echo "\n===========================================\n";
echo "Summary\n";
echo "===========================================\n";
echo "Total Clients Processed: " . $clientsWithoutSavings->count() . "\n";
echo "Successfully Created: {$created}\n";
echo "Failed: {$failed}\n";

if ($failed > 0) {
    echo "\nErrors:\n";
    foreach ($errors as $error) {
        echo "- {$error['client']}: {$error['error']}\n";
    }
}

echo "\n✓ Done!\n";
