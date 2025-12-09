<?php

namespace Modules\Loan\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanLinkedCharge;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Loan\Events\TransactionUpdated;

class ProcessMaturityPenalties extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'loan_maturity_penalties:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply recurring penalties to overdue loans (past maturity date) based on frequency settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today()->format("Y-m-d");
        
        // Get all loans that are past maturity date with overdue maturity charges
        $data = DB::table("loan_product_linked_charges")
            ->join("loan_charges", "loan_charges.id", "loan_product_linked_charges.loan_charge_id")
            ->join("loan_products", "loan_products.id", "loan_product_linked_charges.loan_product_id")
            ->join("loans", "loans.loan_product_id", "loan_products.id")
            ->leftJoin("loan_repayment_schedules", function($join) {
                $join->on("loan_repayment_schedules.loan_id", "=", "loans.id")
                     ->whereRaw("loan_repayment_schedules.due_date = (SELECT MAX(due_date) FROM loan_repayment_schedules WHERE loan_id = loans.id)");
            })
            ->where("loan_charges.loan_charge_type_id", 7) // Overdue on loan maturity
            ->where("loan_charges.is_penalty", 1)
            ->where("loan_charges.schedule", 1) // Only charges with schedule enabled
            ->where("loans.status", "active") // Only active loans
            ->whereRaw("loan_repayment_schedules.due_date < ?", [$today]) // Past maturity
            ->whereRaw("(loans.principal_disbursed_derived - loans.principal_repaid_derived - loans.principal_written_off_derived) > 0") // Still has outstanding balance
            ->selectRaw("
                loan_charges.id as loan_charge_id,
                loan_charges.loan_charge_type_id,
                loan_charges.loan_charge_option_id,
                loan_charges.name as charge_name,
                loan_charges.amount,
                loan_charges.schedule_frequency,
                loan_charges.schedule_frequency_type,
                loans.id as loan_id,
                loans.branch_id,
                loans.principal,
                loans.decimals,
                loan_repayment_schedules.due_date as maturity_date,
                (loans.principal_disbursed_derived - loans.principal_repaid_derived - loans.principal_written_off_derived) as outstanding_principal
            ")
            ->groupBy('loans.id', 'loan_charges.id', 'loan_charges.loan_charge_type_id', 'loan_charges.loan_charge_option_id', 
                     'loan_charges.name', 'loan_charges.amount', 'loan_charges.schedule_frequency', 
                     'loan_charges.schedule_frequency_type', 'loans.branch_id', 'loans.principal', 
                     'loans.decimals', 'loan_repayment_schedules.due_date', 'loans.principal_disbursed_derived',
                     'loans.principal_repaid_derived', 'loans.principal_written_off_derived')
            ->get();

        $processedCount = 0;
        $skippedCount = 0;

        foreach ($data as $key) {
            $loan = Loan::with('repayment_schedules')->find($key->loan_id);
            
            // Check if this charge has been applied before
            $existing_charge = LoanLinkedCharge::where('loan_id', $loan->id)
                ->where('loan_charge_id', $key->loan_charge_id)
                ->where('loan_charge_type_id', 7)
                ->orderBy('last_penalty_applied_date', 'desc')
                ->first();

            // Determine if we should apply the penalty based on frequency
            $shouldApply = false;
            
            if (!$existing_charge) {
                // First time applying this penalty
                $shouldApply = true;
            } else {
                // Check if enough time has passed based on frequency
                $lastApplied = Carbon::parse($existing_charge->last_penalty_applied_date ?? $existing_charge->created_at);
                $daysSinceLastApplied = $lastApplied->diffInDays(Carbon::today());
                
                $frequencyDays = $this->getFrequencyInDays($key->schedule_frequency, $key->schedule_frequency_type);
                
                if ($daysSinceLastApplied >= $frequencyDays) {
                    $shouldApply = true;
                }
            }

            if (!$shouldApply) {
                $skippedCount++;
                continue;
            }

            // Create new linked charge
            $loan_linked_charge = new LoanLinkedCharge();
            $loan_linked_charge->loan_id = $loan->id;
            $loan_linked_charge->name = $key->charge_name;
            $loan_linked_charge->loan_charge_id = $key->loan_charge_id;
            $loan_linked_charge->amount = $key->amount;
            $loan_linked_charge->loan_charge_type_id = $key->loan_charge_type_id;
            $loan_linked_charge->loan_charge_option_id = $key->loan_charge_option_id;
            $loan_linked_charge->is_penalty = 1;
            $loan_linked_charge->due_date = $today;
            $loan_linked_charge->last_penalty_applied_date = $today;
            
            // Calculate the amount based on charge option
            $amount = $this->calculatePenaltyAmount($loan_linked_charge, $loan, $key);
            
            $loan_linked_charge->calculated_amount = $amount;
            $loan_linked_charge->save();

            // Create transaction
            $loan_transaction = new LoanTransaction();
            $loan_transaction->loan_id = $loan->id;
            $loan_transaction->name = trans_choice('loan::general.penalty', 1) . ' ' . trans_choice('loan::general.applied', 1) . ' - ' . $key->charge_name;
            $loan_transaction->loan_transaction_type_id = 10; // Charge applied
            $loan_transaction->submitted_on = $today;
            $loan_transaction->created_on = $today;
            $loan_transaction->amount = $amount;
            $loan_transaction->debit = $amount;
            $loan_transaction->reversible = 1;
            $loan_transaction->save();

            $loan_linked_charge->loan_transaction_id = $loan_transaction->id;
            $loan_linked_charge->save();

            // Fire transaction updated event
            event(new TransactionUpdated($loan));
            
            $processedCount++;
        }

        $this->info("Maturity penalties processed: {$processedCount} applied, {$skippedCount} skipped (not due yet)");
    }

    /**
     * Convert frequency to days
     */
    private function getFrequencyInDays($frequency, $type)
    {
        switch ($type) {
            case 'days':
                return $frequency;
            case 'weeks':
                return $frequency * 7;
            case 'months':
                return $frequency * 30; // Approximate
            case 'years':
                return $frequency * 365;
            default:
                return $frequency;
        }
    }

    /**
     * Calculate penalty amount based on charge option
     */
    private function calculatePenaltyAmount($loan_linked_charge, $loan, $key)
    {
        $amount = 0;
        
        // Get outstanding amounts
        $outstanding_principal = $loan->principal_disbursed_derived - $loan->principal_repaid_derived - $loan->principal_written_off_derived;
        $outstanding_interest = $loan->interest_disbursed_derived - $loan->interest_repaid_derived - $loan->interest_waived_derived - $loan->interest_written_off_derived;

        switch ($loan_linked_charge->loan_charge_option_id) {
            case 1: // Flat
                $amount = $loan_linked_charge->amount;
                break;
            case 2: // % of principal due
                $amount = round(($loan_linked_charge->amount * $outstanding_principal / 100), $loan->decimals);
                break;
            case 3: // % of principal + interest due
                $amount = round(($loan_linked_charge->amount * ($outstanding_principal + $outstanding_interest) / 100), $loan->decimals);
                break;
            case 4: // % of interest due
                $amount = round(($loan_linked_charge->amount * $outstanding_interest / 100), $loan->decimals);
                break;
            case 5: // % of total outstanding principal
                $amount = round(($loan_linked_charge->amount * $outstanding_principal / 100), $loan->decimals);
                break;
            case 6: // % of original loan principal per installment
                $amount = round(($loan_linked_charge->amount * $loan->principal / 100), $loan->decimals);
                break;
            case 7: // % of original loan principal
                $amount = round(($loan_linked_charge->amount * $loan->principal / 100), $loan->decimals);
                break;
        }

        return $amount;
    }
}
