<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Savings\Entities\SavingsTransaction;
use Modules\Income\Entities\Income;
use Modules\Expense\Entities\Expense;
use Carbon\Carbon;

class PerformanceComparison extends AbstractWidget
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
        // Current month data
        $current_month_start = Carbon::now()->startOfMonth();
        $current_month_end = Carbon::now()->endOfMonth();
        
        // Previous month data
        $previous_month_start = Carbon::now()->subMonth()->startOfMonth();
        $previous_month_end = Carbon::now()->subMonth()->endOfMonth();
        
        // Same month last year
        $last_year_month_start = Carbon::now()->subYear()->startOfMonth();
        $last_year_month_end = Carbon::now()->subYear()->endOfMonth();

        // Loan disbursements comparison
        $current_disbursements = LoanTransaction::where('reversed', 0)
            ->where('loan_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$current_month_start, $current_month_end])
            ->sum('amount');

        $previous_disbursements = LoanTransaction::where('reversed', 0)
            ->where('loan_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$previous_month_start, $previous_month_end])
            ->sum('amount');

        $last_year_disbursements = LoanTransaction::where('reversed', 0)
            ->where('loan_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$last_year_month_start, $last_year_month_end])
            ->sum('amount');

        // Loan repayments comparison
        $current_repayments = LoanTransaction::where('reversed', 0)
            ->whereIn('loan_transaction_type_id', [2, 5, 8])
            ->whereBetween('submitted_on', [$current_month_start, $current_month_end])
            ->sum('amount');

        $previous_repayments = LoanTransaction::where('reversed', 0)
            ->whereIn('loan_transaction_type_id', [2, 5, 8])
            ->whereBetween('submitted_on', [$previous_month_start, $previous_month_end])
            ->sum('amount');

        $last_year_repayments = LoanTransaction::where('reversed', 0)
            ->whereIn('loan_transaction_type_id', [2, 5, 8])
            ->whereBetween('submitted_on', [$last_year_month_start, $last_year_month_end])
            ->sum('amount');

        // Income comparison
        $current_income = Income::whereBetween('date', [$current_month_start, $current_month_end])->sum('amount');
        $previous_income = Income::whereBetween('date', [$previous_month_start, $previous_month_end])->sum('amount');
        $last_year_income = Income::whereBetween('date', [$last_year_month_start, $last_year_month_end])->sum('amount');

        // Expense comparison
        $current_expenses = Expense::whereBetween('date', [$current_month_start, $current_month_end])->sum('amount');
        $previous_expenses = Expense::whereBetween('date', [$previous_month_start, $previous_month_end])->sum('amount');
        $last_year_expenses = Expense::whereBetween('date', [$last_year_month_start, $last_year_month_end])->sum('amount');

        // Savings deposits comparison
        $current_savings = SavingsTransaction::where('reversed', 0)
            ->where('savings_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$current_month_start, $current_month_end])
            ->sum('amount');

        $previous_savings = SavingsTransaction::where('reversed', 0)
            ->where('savings_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$previous_month_start, $previous_month_end])
            ->sum('amount');

        $last_year_savings = SavingsTransaction::where('reversed', 0)
            ->where('savings_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$last_year_month_start, $last_year_month_end])
            ->sum('amount');

        // Calculate percentage changes
        $disbursements_mom_change = $previous_disbursements > 0 ? (($current_disbursements - $previous_disbursements) / $previous_disbursements) * 100 : 0;
        $disbursements_yoy_change = $last_year_disbursements > 0 ? (($current_disbursements - $last_year_disbursements) / $last_year_disbursements) * 100 : 0;

        $repayments_mom_change = $previous_repayments > 0 ? (($current_repayments - $previous_repayments) / $previous_repayments) * 100 : 0;
        $repayments_yoy_change = $last_year_repayments > 0 ? (($current_repayments - $last_year_repayments) / $last_year_repayments) * 100 : 0;

        $income_mom_change = $previous_income > 0 ? (($current_income - $previous_income) / $previous_income) * 100 : 0;
        $income_yoy_change = $last_year_income > 0 ? (($current_income - $last_year_income) / $last_year_income) * 100 : 0;

        $expenses_mom_change = $previous_expenses > 0 ? (($current_expenses - $previous_expenses) / $previous_expenses) * 100 : 0;
        $expenses_yoy_change = $last_year_expenses > 0 ? (($current_expenses - $last_year_expenses) / $last_year_expenses) * 100 : 0;

        $savings_mom_change = $previous_savings > 0 ? (($current_savings - $previous_savings) / $previous_savings) * 100 : 0;
        $savings_yoy_change = $last_year_savings > 0 ? (($current_savings - $last_year_savings) / $last_year_savings) * 100 : 0;

        // Net profit calculations
        $current_profit = $current_income - $current_expenses;
        $previous_profit = $previous_income - $previous_expenses;
        $last_year_profit = $last_year_income - $last_year_expenses;

        $profit_mom_change = $previous_profit != 0 ? (($current_profit - $previous_profit) / abs($previous_profit)) * 100 : 0;
        $profit_yoy_change = $last_year_profit != 0 ? (($current_profit - $last_year_profit) / abs($last_year_profit)) * 100 : 0;

        return theme_view('dashboard::widgets.performance_comparison', [
            'config' => $this->config,
            'current_month' => Carbon::now()->format('M Y'),
            'previous_month' => Carbon::now()->subMonth()->format('M Y'),
            'last_year_month' => Carbon::now()->subYear()->format('M Y'),
            
            // Current values
            'current_disbursements' => $current_disbursements,
            'current_repayments' => $current_repayments,
            'current_income' => $current_income,
            'current_expenses' => $current_expenses,
            'current_savings' => $current_savings,
            'current_profit' => $current_profit,
            
            // Previous month values
            'previous_disbursements' => $previous_disbursements,
            'previous_repayments' => $previous_repayments,
            'previous_income' => $previous_income,
            'previous_expenses' => $previous_expenses,
            'previous_savings' => $previous_savings,
            'previous_profit' => $previous_profit,
            
            // Year over year values
            'last_year_disbursements' => $last_year_disbursements,
            'last_year_repayments' => $last_year_repayments,
            'last_year_income' => $last_year_income,
            'last_year_expenses' => $last_year_expenses,
            'last_year_savings' => $last_year_savings,
            'last_year_profit' => $last_year_profit,
            
            // Percentage changes
            'disbursements_mom_change' => $disbursements_mom_change,
            'disbursements_yoy_change' => $disbursements_yoy_change,
            'repayments_mom_change' => $repayments_mom_change,
            'repayments_yoy_change' => $repayments_yoy_change,
            'income_mom_change' => $income_mom_change,
            'income_yoy_change' => $income_yoy_change,
            'expenses_mom_change' => $expenses_mom_change,
            'expenses_yoy_change' => $expenses_yoy_change,
            'savings_mom_change' => $savings_mom_change,
            'savings_yoy_change' => $savings_yoy_change,
            'profit_mom_change' => $profit_mom_change,
            'profit_yoy_change' => $profit_yoy_change,
        ]);
    }
}
