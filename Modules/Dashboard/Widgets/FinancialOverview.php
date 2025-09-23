<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Savings\Entities\Savings;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Income\Entities\Income;
use Modules\Expense\Entities\Expense;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletTransaction;

class FinancialOverview extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $date_range = request('date_range');
        $start_date = '';
        $end_date = '';
        
        if (!empty($date_range)) {
            $exploded_date = explode('to', $date_range);
            $start_date = trim($exploded_date[0]);
            $end_date = trim($exploded_date[1]);
        }

        // Loan Portfolio Metrics
        $total_loans_disbursed = Loan::where('status', 'active')
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('disbursed_on_date', [$start_date, $end_date]);
            })
            ->sum('principal');

        $total_loan_repayments = LoanTransaction::where('reversed', 0)
            ->whereIn('loan_transaction_type_id', [2, 5, 8])
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('submitted_on', [$start_date, $end_date]);
            })
            ->sum('amount');

        // Savings Metrics
        $total_savings_balance = SavingsTransaction::where('reversed', 0)
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('submitted_on', [$start_date, $end_date]);
            })
            ->selectRaw('SUM(CASE WHEN savings_transaction_type_id = 1 THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        $total_savings_deposits = SavingsTransaction::where('reversed', 0)
            ->where('savings_transaction_type_id', 1)
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('submitted_on', [$start_date, $end_date]);
            })
            ->sum('amount');

        // Income & Expense Metrics
        $total_income = Income::when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            })
            ->sum('amount');

        $total_expenses = Expense::when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            })
            ->sum('amount');

        // Wallet Metrics
        $total_wallet_balance = WalletTransaction::where('reversed', 0)
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('submitted_on', [$start_date, $end_date]);
            })
            ->selectRaw('SUM(CASE WHEN wallet_transaction_type_id = 1 THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        // Calculate Portfolio Health Metrics
        $active_loans = Loan::where('status', 'active')->get();
        $total_outstanding = 0;
        $total_overdue = 0;
        
        foreach ($active_loans as $loan) {
            $outstanding = $loan->repayment_schedules->sum('principal') + 
                          $loan->repayment_schedules->sum('interest') + 
                          $loan->repayment_schedules->sum('fees') + 
                          $loan->repayment_schedules->sum('penalties') -
                          $loan->repayment_schedules->sum('principal_repaid_derived') -
                          $loan->repayment_schedules->sum('interest_repaid_derived') -
                          $loan->repayment_schedules->sum('fees_repaid_derived') -
                          $loan->repayment_schedules->sum('penalties_repaid_derived');
            
            $total_outstanding += $outstanding;
            
            // Calculate overdue amount
            $overdue_schedules = $loan->repayment_schedules->where('due_date', '<', date('Y-m-d'))->where('total_due', '>', 0);
            foreach ($overdue_schedules as $schedule) {
                $overdue_amount = ($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalties) -
                                 ($schedule->principal_repaid_derived + $schedule->interest_repaid_derived + 
                                  $schedule->fees_repaid_derived + $schedule->penalties_repaid_derived);
                $total_overdue += max(0, $overdue_amount);
            }
        }

        // Calculate Portfolio at Risk (PAR)
        $par_ratio = $total_outstanding > 0 ? ($total_overdue / $total_outstanding) * 100 : 0;

        // Net Profit/Loss
        $net_profit = $total_income - $total_expenses;

        // Cash Flow
        $cash_inflow = $total_loan_repayments + $total_savings_deposits + $total_income;
        $cash_outflow = $total_loans_disbursed + $total_expenses;
        $net_cash_flow = $cash_inflow - $cash_outflow;

        return theme_view('dashboard::widgets.financial_overview', [
            'config' => $this->config,
            'total_loans_disbursed' => $total_loans_disbursed,
            'total_loan_repayments' => $total_loan_repayments,
            'total_savings_balance' => $total_savings_balance,
            'total_savings_deposits' => $total_savings_deposits,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'total_wallet_balance' => $total_wallet_balance,
            'total_outstanding' => $total_outstanding,
            'total_overdue' => $total_overdue,
            'par_ratio' => $par_ratio,
            'net_profit' => $net_profit,
            'cash_inflow' => $cash_inflow,
            'cash_outflow' => $cash_outflow,
            'net_cash_flow' => $net_cash_flow,
        ]);
    }
}
