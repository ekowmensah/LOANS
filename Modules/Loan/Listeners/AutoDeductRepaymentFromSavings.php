<?php

namespace Modules\Loan\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Entities\JournalEntry;
use Modules\Core\Entities\PaymentDetail;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Loan\Events\RepaymentDue;
use Modules\Loan\Events\TransactionUpdated;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Savings\Events\TransactionUpdated as SavingsTransactionUpdated;
use Modules\Setting\Entities\Setting;

class AutoDeductRepaymentFromSavings
{
    /**
     * Handle the event.
     *
     * @param RepaymentDue $event
     * @return void
     */
    public function handle(RepaymentDue $event)
    {
        $loan = $event->loan;
        $dueAmount = $event->dueAmount;
        $dueDate = $event->dueDate;

        // Check if auto-deduction is enabled
        $autoDeduct = Setting::where('setting_key', 'auto_deduct_loan_repayment_from_savings')->first();
        if (!$autoDeduct || $autoDeduct->setting_value != '1') {
            return;
        }

        try {
            DB::beginTransaction();

            // Handle Individual Loans
            if ($loan->client_type === 'individual' || $loan->client_type === 'client') {
                $this->deductFromClientSavings($loan, $loan->client_id, $dueAmount, $dueDate);
            }

            // Handle Group Loans - deduct from each member's savings proportionally
            if ($loan->client_type === 'group' && $loan->memberAllocations && $loan->memberAllocations->count() > 0) {
                foreach ($loan->memberAllocations as $allocation) {
                    // Calculate member's proportional repayment amount
                    $memberDueAmount = ($allocation->outstanding_balance > 0) 
                        ? min($allocation->outstanding_balance, $dueAmount * ($allocation->allocated_amount / $loan->principal))
                        : 0;
                    
                    if ($memberDueAmount > 0) {
                        $this->deductFromClientSavings(
                            $loan,
                            $allocation->client_id,
                            $memberDueAmount,
                            $dueDate,
                            "Group Loan Repayment Deduction"
                        );
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to auto-deduct loan repayment from savings: ' . $e->getMessage(), [
                'loan_id' => $loan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Deduct repayment from client's savings account
     *
     * @param $loan
     * @param $clientId
     * @param $amount
     * @param $date
     * @param string $note
     * @return void
     */
    private function deductFromClientSavings($loan, $clientId, $amount, $date, $note = "Loan Repayment Auto-Deduction")
    {
        // Find client's active savings account
        $savings = Savings::where('client_id', $clientId)
            ->where('status', 'active')
            ->first();

        if (!$savings) {
            Log::warning("Client {$clientId} does not have an active savings account. Auto-deduction skipped.", [
                'loan_id' => $loan->id,
                'client_id' => $clientId,
                'amount' => $amount
            ]);
            return;
        }

        // Check if sufficient balance
        if ($savings->balance_derived < $amount) {
            Log::warning("Insufficient savings balance for auto-deduction. Available: {$savings->balance_derived}, Required: {$amount}", [
                'loan_id' => $loan->id,
                'client_id' => $clientId,
                'savings_id' => $savings->id,
                'balance' => $savings->balance_derived,
                'amount' => $amount
            ]);
            return;
        }

        // Create payment detail
        $payment_detail = new PaymentDetail();
        $payment_detail->created_by_id = Auth::id() ?? 1; // System user if no auth
        $payment_detail->payment_type_id = 1; // Internal transfer
        $payment_detail->transaction_type = 'savings_transaction';
        $payment_detail->description = "{$note} - Loan #{$loan->id}";
        $payment_detail->save();

        // Create savings withdrawal transaction
        $savings_transaction = new SavingsTransaction();
        $savings_transaction->created_by_id = Auth::id() ?? 1;
        $savings_transaction->savings_id = $savings->id;
        $savings_transaction->branch_id = $savings->branch_id;
        $savings_transaction->payment_detail_id = $payment_detail->id;
        $savings_transaction->name = "{$note} - Loan #{$loan->id}";
        $savings_transaction->savings_transaction_type_id = 2; // Withdrawal
        $savings_transaction->submitted_on = $date;
        $savings_transaction->created_on = date("Y-m-d");
        $savings_transaction->reversible = 0;
        $savings_transaction->amount = $amount;
        $savings_transaction->debit = $amount;
        $savings_transaction->save();

        // Update savings balance
        $savings->balance_derived = $savings->balance_derived - $amount;
        $savings->total_withdrawals_derived = $savings->total_withdrawals_derived + $amount;
        $savings->save();

        // Create loan repayment transaction
        $loan_transaction = new LoanTransaction();
        $loan_transaction->created_by_id = Auth::id() ?? 1;
        $loan_transaction->loan_id = $loan->id;
        $loan_transaction->payment_detail_id = $payment_detail->id;
        $loan_transaction->name = 'Auto-Deducted Repayment from Savings';
        $loan_transaction->loan_transaction_type_id = 2; // Repayment
        $loan_transaction->submitted_on = $date;
        $loan_transaction->created_on = date("Y-m-d");
        $loan_transaction->amount = $amount;
        $loan_transaction->credit = $amount;
        $loan_transaction->save();

        // Create journal entries if using cash accounting
        if ($savings->savings_product->accounting_rule == 'cash') {
            $date_parts = explode('-', $date);
            
            // Debit savings control account (reducing liability)
            $journal_entry = new JournalEntry();
            $journal_entry->created_by_id = Auth::id() ?? 1;
            $journal_entry->transaction_number = 'LR-S' . $savings_transaction->id;
            $journal_entry->branch_id = $savings->branch_id;
            $journal_entry->currency_id = $savings->currency_id;
            $journal_entry->chart_of_account_id = $savings->savings_product->savings_control_chart_of_account_id;
            $journal_entry->transaction_type = 'loan_repayment_from_savings';
            $journal_entry->date = $date;
            $journal_entry->month = $date_parts[1];
            $journal_entry->year = $date_parts[0];
            $journal_entry->debit = $amount;
            $journal_entry->reference = $loan->id;
            $journal_entry->notes = "Auto-deducted loan repayment from savings #{$savings->id} for loan #{$loan->id}";
            $journal_entry->save();

            // Credit loan portfolio account (reducing asset)
            if (!empty($loan->loan_product->loan_portfolio_chart_of_account_id)) {
                $journal_entry = new JournalEntry();
                $journal_entry->created_by_id = Auth::id() ?? 1;
                $journal_entry->transaction_number = 'LR-S' . $savings_transaction->id;
                $journal_entry->branch_id = $savings->branch_id;
                $journal_entry->currency_id = $savings->currency_id;
                $journal_entry->chart_of_account_id = $loan->loan_product->loan_portfolio_chart_of_account_id;
                $journal_entry->transaction_type = 'loan_repayment_from_savings';
                $journal_entry->date = $date;
                $journal_entry->month = $date_parts[1];
                $journal_entry->year = $date_parts[0];
                $journal_entry->credit = $amount;
                $journal_entry->reference = $loan->id;
                $journal_entry->notes = "Auto-deducted loan repayment from savings #{$savings->id} for loan #{$loan->id}";
                $journal_entry->save();
            }
        }

        // Log activity
        activity()->on($loan_transaction)
            ->withProperties([
                'id' => $loan_transaction->id,
                'savings_id' => $savings->id,
                'auto_deducted' => true
            ])
            ->log('Auto-deducted Loan Repayment from Savings');

        // Fire transaction updated events
        event(new TransactionUpdated($loan));
        event(new SavingsTransactionUpdated($savings));

        Log::info("Loan repayment auto-deducted from savings", [
            'loan_id' => $loan->id,
            'client_id' => $clientId,
            'savings_id' => $savings->id,
            'amount' => $amount,
            'loan_transaction_id' => $loan_transaction->id,
            'savings_transaction_id' => $savings_transaction->id
        ]);
    }
}
