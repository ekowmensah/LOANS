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

        // Create a reversal transaction (debit to offset the credit)
        $reversal_transaction = new SavingsTransaction();
        $reversal_transaction->created_by_id = Auth::id();
        $reversal_transaction->savings_id = $savings->id;
        $reversal_transaction->branch_id = $savings->branch_id;
        $reversal_transaction->payment_detail_id = $savingsTransaction->payment_detail_id;
        $reversal_transaction->name = "REVERSAL: " . $savingsTransaction->name;
        $reversal_transaction->savings_transaction_type_id = 2; // Withdrawal
        $reversal_transaction->submitted_on = date("Y-m-d");
        $reversal_transaction->created_on = date("Y-m-d");
        $reversal_transaction->reversible = 0;
        $reversal_transaction->amount = $savingsTransaction->amount;
        $reversal_transaction->debit = $savingsTransaction->amount; // Debit to reverse the credit
        $reversal_transaction->description = "Reversal of loan disbursement - Loan #{$loan->id} undisbursed";
        $reversal_transaction->save();

        // Mark the original savings transaction as reversed
        $savingsTransaction->reversed = 1;
        $savingsTransaction->save();

        // Create reversal journal entries if using cash accounting
        if ($savings->savings_product && $savings->savings_product->accounting_rule == 'cash') {
            // Debit savings control account (reverse the credit)
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'LDR-S' . $reversal_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_control_chart_of_account_id;
            $journal_entry->transaction_type = 'loan_disbursement_reversal';
            $journal_entry->date = date("Y-m-d");
            $date_parts = explode('-', date("Y-m-d"));
            $journal_entry->month = $date_parts[1];
            $journal_entry->year = $date_parts[0];
            $journal_entry->debit = $savingsTransaction->amount; // Debit to reverse
            $journal_entry->reference = $savings->id;
            $journal_entry->notes = "Reversal: Loan #{$loan->id} undisbursed - savings #{$savings->id}";
            $journal_entry->save();

            // Debit savings reference account
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'LDR-S' . $reversal_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_reference_chart_of_account_id;
            $journal_entry->transaction_type = 'loan_disbursement_reversal';
            $journal_entry->date = date("Y-m-d");
            $journal_entry->month = $date_parts[1];
            $journal_entry->year = $date_parts[0];
            $journal_entry->credit = $savingsTransaction->amount; // Credit to balance
            $journal_entry->reference = $savings->id;
            $journal_entry->notes = "Reversal: Loan #{$loan->id} undisbursed - savings #{$savings->id}";
            $journal_entry->save();
        }

        // Also mark old journal entries as reversed
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
