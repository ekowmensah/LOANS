<?php

namespace Modules\Loan\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Entities\JournalEntry;
use Modules\Core\Entities\PaymentDetail;
use Modules\Loan\Events\LoanDisbursed;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Savings\Events\TransactionUpdated;
use Modules\Setting\Entities\Setting;

class DepositDisbursementToSavings
{
    /**
     * Handle the event.
     *
     * @param LoanDisbursed $event
     * @return void
     */
    public function handle(LoanDisbursed $event)
    {
        $loan = $event->loan;
        $disbursementAmount = $event->disbursementAmount;
        $disbursementDate = $event->disbursementDate;

        // Check if auto-deposit is enabled
        $autoDeposit = Setting::where('setting_key', 'auto_deposit_loan_to_savings')->first();
        if (!$autoDeposit || $autoDeposit->setting_value != '1') {
            return;
        }

        try {
            // Handle Individual Loans
            if ($loan->client_type === 'individual' || $loan->client_type === 'client') {
                $this->depositToClientSavings($loan, $loan->client_id, $disbursementAmount, $disbursementDate);
            }

            // Handle Group Loans - deposit to each member's savings
            if ($loan->client_type === 'group' && $loan->memberAllocations && $loan->memberAllocations->count() > 0) {
                foreach ($loan->memberAllocations as $allocation) {
                    $this->depositToClientSavings(
                        $loan,
                        $allocation->client_id,
                        $allocation->allocated_amount,
                        $disbursementDate,
                        "Group Loan Member Allocation"
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to auto-deposit loan disbursement to savings: ' . $e->getMessage(), [
                'loan_id' => $loan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Deposit disbursement to client's savings account
     *
     * @param $loan
     * @param $clientId
     * @param $amount
     * @param $date
     * @param string $note
     * @return void
     */
    private function depositToClientSavings($loan, $clientId, $amount, $date, $note = "Loan Disbursement")
    {
        // Find client's active savings account
        $savings = Savings::where('client_id', $clientId)
            ->where('status', 'active')
            ->first();

        if (!$savings) {
            Log::warning("Client {$clientId} does not have an active savings account. Loan disbursement not deposited.", [
                'loan_id' => $loan->id,
                'client_id' => $clientId,
                'amount' => $amount
            ]);
            return;
        }

        // Create payment detail
        $payment_detail = new PaymentDetail();
        $payment_detail->created_by_id = Auth::id();
        $payment_detail->payment_type_id = 1; // Cash/Internal transfer
        $payment_detail->transaction_type = 'savings_transaction';
        $payment_detail->description = "{$note} - Loan #{$loan->id}";
        $payment_detail->save();

        // Create savings deposit transaction
        $savings_transaction = new SavingsTransaction();
        $savings_transaction->created_by_id = Auth::id();
        $savings_transaction->savings_id = $savings->id;
        $savings_transaction->branch_id = $savings->branch_id;
        $savings_transaction->payment_detail_id = $payment_detail->id;
        $savings_transaction->name = "{$note} - Loan #{$loan->id}";
        $savings_transaction->savings_transaction_type_id = 1; // Deposit
        $savings_transaction->submitted_on = $date;
        $savings_transaction->created_on = date("Y-m-d");
        $savings_transaction->reversible = 0; // Loan disbursements should not be easily reversed
        $savings_transaction->amount = $amount;
        $savings_transaction->credit = $amount;
        $savings_transaction->save();

        // Create journal entries if using cash accounting
        if ($savings->savings_product->accounting_rule == 'cash') {
            // Credit savings control account
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'LD-S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_control_chart_of_account_id;
            $journal_entry->transaction_type = 'loan_disbursement_to_savings';
            $journal_entry->date = $date;
            $date_parts = explode('-', $date);
            $journal_entry->month = $date_parts[1];
            $journal_entry->year = $date_parts[0];
            $journal_entry->credit = $amount;
            $journal_entry->reference = $savings->id;
            $journal_entry->notes = "Loan #{$loan->id} disbursement to savings #{$savings->id}";
            $journal_entry->save();

            // Debit savings reference account (cash/bank)
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id();
            $journal_entry->transaction_number = 'LD-S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_reference_chart_of_account_id;
            $journal_entry->transaction_type = 'loan_disbursement_to_savings';
            $journal_entry->date = $date;
            $journal_entry->month = $date_parts[1];
            $journal_entry->year = $date_parts[0];
            $journal_entry->debit = $amount;
            $journal_entry->reference = $savings->id;
            $journal_entry->notes = "Loan #{$loan->id} disbursement to savings #{$savings->id}";
            $journal_entry->save();
        }

        // Log activity
        activity()->on($savings_transaction)
            ->withProperties([
                'id' => $savings_transaction->id,
                'loan_id' => $loan->id,
                'auto_deposited' => true
            ])
            ->log('Auto-deposited Loan Disbursement to Savings');

        // Fire transaction updated event for savings
        event(new TransactionUpdated($savings));

        Log::info("Loan disbursement deposited to savings", [
            'loan_id' => $loan->id,
            'client_id' => $clientId,
            'savings_id' => $savings->id,
            'amount' => $amount,
            'transaction_id' => $savings_transaction->id
        ]);
    }
}
