<?php

namespace Modules\Client\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Client\Events\ClientCreated;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsProduct;
use Modules\Setting\Entities\Setting;

class CreateDefaultSavingsAccount
{
    /**
     * Handle the event.
     *
     * @param ClientCreated $event
     * @return void
     */
    public function handle(ClientCreated $event)
    {
        $client = $event->client;

        // Check if auto-create is enabled
        $autoCreate = Setting::where('setting_key', 'auto_create_savings_account')->first();
        if (!$autoCreate || $autoCreate->setting_value != '1') {
            return;
        }

        // Get default savings product
        $defaultProductId = Setting::where('setting_key', 'default_savings_product_id')->first();
        if (!$defaultProductId || empty($defaultProductId->setting_value)) {
            Log::warning('Default savings product not configured. Cannot auto-create savings account for client: ' . $client->id);
            return;
        }

        $savingsProduct = SavingsProduct::find($defaultProductId->setting_value);
        if (!$savingsProduct) {
            Log::warning('Default savings product not found. Cannot auto-create savings account for client: ' . $client->id);
            return;
        }

        try {
            // Create savings account
            $savings = new Savings();
            $savings->currency_id = $savingsProduct->currency_id;
            $savings->created_by_id = Auth::id();
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
            $savings->automatic_opening_balance = 0; // Start with zero balance
            $savings->lockin_period = $savingsProduct->lockin_period ?? 0;
            $savings->lockin_type = $savingsProduct->lockin_type ?? 'days';
            $savings->allow_overdraft = $savingsProduct->allow_overdraft;
            $savings->overdraft_limit = $savingsProduct->overdraft_limit;
            $savings->overdraft_interest_rate = $savingsProduct->overdraft_interest_rate;
            $savings->minimum_overdraft_for_interest = $savingsProduct->minimum_overdraft_for_interest;
            $savings->submitted_on_date = $client->created_date ?? date('Y-m-d');
            $savings->submitted_by_user_id = Auth::id();
            $savings->status = 'active'; // Auto-approve the account
            $savings->approved_on_date = date('Y-m-d');
            $savings->approved_by_user_id = Auth::id();
            $savings->activated_on_date = date('Y-m-d');
            $savings->activated_by_user_id = Auth::id();
            $savings->save();

            // Generate account number
            $savings->account_number = generate_savings_reference('savings.reference_prefix', $savings);
            $savings->save();

            activity()->on($savings)
                ->withProperties(['id' => $savings->id, 'auto_created' => true])
                ->log('Auto-created Savings Account for Client');

            Log::info('Auto-created savings account ' . $savings->account_number . ' for client: ' . $client->id);
        } catch (\Exception $e) {
            Log::error('Failed to auto-create savings account for client ' . $client->id . ': ' . $e->getMessage());
        }
    }
}
