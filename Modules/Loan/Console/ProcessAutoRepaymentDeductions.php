<?php

namespace Modules\Loan\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanRepaymentSchedule;
use Modules\Loan\Events\RepaymentDue;
use Modules\Setting\Entities\Setting;

class ProcessAutoRepaymentDeductions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:process-auto-repayment-deductions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automatic loan repayment deductions from savings accounts for due installments';

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
        // Check if auto-deduction is enabled
        $autoDeduct = Setting::where('setting_key', 'auto_deduct_loan_repayment_from_savings')->first();
        if (!$autoDeduct || $autoDeduct->setting_value != '1') {
            $this->info('Auto-deduction is disabled. Skipping.');
            return 0;
        }

        $today = date('Y-m-d');
        $processedCount = 0;
        $failedCount = 0;

        $this->info("Processing auto-repayment deductions for date: {$today}");

        // Find all active loans with schedules due today
        $dueSchedules = LoanRepaymentSchedule::where('due_date', $today)
            ->whereHas('loan', function ($query) {
                $query->where('status', 'active');
            })
            ->with('loan')
            ->get();

        $this->info("Found {$dueSchedules->count()} due installments for today.");

        foreach ($dueSchedules as $schedule) {
            $loan = $schedule->loan;
            
            // Calculate outstanding amount for this schedule
            $principal_due = $schedule->principal - $schedule->principal_repaid_derived - $schedule->principal_written_off_derived;
            $interest_due = $schedule->interest - $schedule->interest_repaid_derived - $schedule->interest_waived_derived - $schedule->interest_written_off_derived;
            $fees_due = $schedule->fees - $schedule->fees_repaid_derived - $schedule->fees_waived_derived - $schedule->fees_written_off_derived;
            $penalties_due = $schedule->penalties - $schedule->penalties_repaid_derived - $schedule->penalties_waived_derived - $schedule->penalties_written_off_derived;
            
            $total_due = $principal_due + $interest_due + $fees_due + $penalties_due;

            // Skip if already fully paid
            if ($total_due <= 0) {
                continue;
            }

            try {
                // Fire event to trigger auto-deduction
                event(new RepaymentDue($loan, $total_due, $today));
                
                $processedCount++;
                $this->info("Processed loan #{$loan->id} - Amount: {$total_due}");
                
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("Failed to process loan #{$loan->id}: " . $e->getMessage());
                Log::error("Auto-deduction failed for loan #{$loan->id}", [
                    'loan_id' => $loan->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("Auto-repayment deduction processing complete.");
        $this->info("Processed: {$processedCount}, Failed: {$failedCount}");

        return 0;
    }
}
