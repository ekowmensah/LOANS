<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Income\Entities\Income;
use Modules\Expense\Entities\Expense;
use Carbon\Carbon;

class CashFlowChart extends AbstractWidget
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
        $start_date = Carbon::now()->subMonths(6)->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        
        if (!empty($date_range)) {
            $exploded_date = explode('to', $date_range);
            $start_date = Carbon::parse(trim($exploded_date[0]))->startOfMonth();
            $end_date = Carbon::parse(trim($exploded_date[1]))->endOfMonth();
        }

        $months = [];
        $cash_inflow = [];
        $cash_outflow = [];
        $net_flow = [];

        $current_date = $start_date->copy();
        while ($current_date <= $end_date) {
            $month_start = $current_date->copy()->startOfMonth();
            $month_end = $current_date->copy()->endOfMonth();
            
            // Cash Inflows (Loan Repayments + Savings Deposits + Income)
            $loan_repayments = LoanTransaction::where('reversed', 0)
                ->whereIn('loan_transaction_type_id', [2, 5, 8])
                ->whereBetween('submitted_on', [$month_start, $month_end])
                ->sum('amount');

            $savings_deposits = SavingsTransaction::where('reversed', 0)
                ->where('savings_transaction_type_id', 1)
                ->whereBetween('submitted_on', [$month_start, $month_end])
                ->sum('amount');

            $income = Income::whereBetween('date', [$month_start, $month_end])
                ->sum('amount');

            $total_inflow = $loan_repayments + $savings_deposits + $income;

            // Cash Outflows (Loan Disbursements + Savings Withdrawals + Expenses)
            $loan_disbursements = LoanTransaction::where('reversed', 0)
                ->where('loan_transaction_type_id', 1)
                ->whereBetween('submitted_on', [$month_start, $month_end])
                ->sum('amount');

            $savings_withdrawals = SavingsTransaction::where('reversed', 0)
                ->where('savings_transaction_type_id', 2)
                ->whereBetween('submitted_on', [$month_start, $month_end])
                ->sum('amount');

            $expenses = Expense::whereBetween('date', [$month_start, $month_end])
                ->sum('amount');

            $total_outflow = $loan_disbursements + $savings_withdrawals + $expenses;

            $months[] = $current_date->format('M Y');
            $cash_inflow[] = round($total_inflow, 2);
            $cash_outflow[] = round($total_outflow, 2);
            $net_flow[] = round($total_inflow - $total_outflow, 2);

            $current_date->addMonth();
        }

        return theme_view('dashboard::widgets.cash_flow_chart', [
            'config' => $this->config,
            'months' => json_encode($months),
            'cash_inflow' => json_encode($cash_inflow),
            'cash_outflow' => json_encode($cash_outflow),
            'net_flow' => json_encode($net_flow),
        ]);
    }
}
