<?php

namespace Modules\Loan\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Entities\JournalEntry;
use Modules\Loan\Events\LoanDisbursementUndone;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsProduct;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Savings\Events\TransactionUpdated;
use Modules\Setting\Entities\Setting;

class ReverseSavingsDeposit
{
    /**
     * Handle the event.
     *
     * @param LoanDisbursementUndone $event
     * @return void
     */
    public function handle(LoanDisbursementUndone $event)
    {
        $loan = $event->loan;

        // Check if auto-deposit is enabled
        $autoDeposit = Setting::where('setting_key', 'auto_deposit_loan_to_savings')->first();
        if (!$autoDeposit || $autoDeposit->setting_value != '1') {
            return;
        }

        try {
            // Handle Individual Loans
            if ($loan->client_type === 'individual' || $loan->client_type === 'client') {
                $this->reverseSavingsDeposit($loan, $loan->client_id);
            }

            // Handle Group Loans - reverse each member's deposit
            if ($loan->client_type === 'group' && $loan->memberAllocations && $loan->memberAllocations->count() > 0) {
                foreach ($loan->memberAllocations as $allocation) {
                    $this->reverseSavingsDeposit($loan, $allocation->client_id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to reverse savings deposit on loan undisbursement: ' . $e->getMessage(), [
                'loan_id' => $loan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Reverse savings deposit for a specific client
     *
     * @param $loan
     * @param $clientId
     * @return void
     */
    private function reverseSavingsDeposit($loan, $clientId)
    {
        // Find the client's active savings account
        $savings = Savings::where('client_id', $clientId)
            ->where('status', 'active')
            ->first();

        if (!$savings) {
            Log::warning("Cannot reverse savings deposit: Client {$clientId} does not have an active savings account", [
                'loan_id' => $loan->id,
                'client_id' => $clientId
            ]);
            return;
        }

        // Find the savings transaction related to this loan disbursement
        $savingsTransaction = SavingsTransaction::where('savings_id', $savings->id)
            ->where('name', 'LIKE', "%Loan #{$loan->id}%")
            ->where('savings_transaction_type_id', 1) // Deposit
            ->where('reversed', 0)
            ->first();

        if (!$savingsTransaction) {
            Log::warning("No savings deposit transaction found to reverse for loan {$loan->id}", [
                'loan_id' => $loan->id,
                'client_id' => $clientId,
                'savings_id' => $savings->id
            ]);
            return;
        }

        // Simply mark the original savings transaction as reversed
        // No need to create a separate reversal transaction
        // The balance calculation already excludes reversed transactions
        $savingsTransaction->reversed = 1;
        $savingsTransaction->save();

        // Update the savings account balance by subtracting the reversed amount
        $savings->balance_derived = $savings->balance_derived - $savingsTransaction->amount;
        $savings->total_deposits_derived = $savings->total_deposits_derived - $savingsTransaction->amount;
        $savings->save();

        // Mark the journal entries as reversed
        JournalEntry::where('transaction_number', 'LD-S' . $savingsTransaction->id)
            ->update(['reversed' => 1]);

        // Log the activity
        activity()->on($savingsTransaction)
            ->withProperties([
                'id' => $savingsTransaction->id,
                'loan_id' => $loan->id,
                'amount' => $savingsTransaction->amount
            ])
            ->log('Reversed Savings Deposit from Loan Disbursement');

        // Fire savings transaction updated event
        event(new TransactionUpdated($savings));

        Log::info("Reversed savings deposit for loan undisbursement", [
            'loan_id' => $loan->id,
            'client_id' => $clientId,
            'savings_id' => $savings->id,
            'transaction_id' => $savingsTransaction->id,
            'amount' => $savingsTransaction->amount
        ]);
    }
}
