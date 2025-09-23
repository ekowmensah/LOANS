<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Savings\Entities\Savings;
use Modules\Income\Entities\Income;
use Modules\Expense\Entities\Expense;
use Carbon\Carbon;

class FinancialAlerts extends AbstractWidget
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
        $alerts = [];

        // Check for high PAR (Portfolio at Risk)
        $active_loans = Loan::where('status', 'active')->with('repayment_schedules')->get();
        $total_outstanding = 0;
        $total_overdue = 0;

        foreach ($active_loans as $loan) {
            foreach ($loan->repayment_schedules as $schedule) {
                $schedule_outstanding = ($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalties) -
                                      ($schedule->principal_repaid_derived + $schedule->interest_repaid_derived + 
                                       $schedule->fees_repaid_derived + $schedule->penalties_repaid_derived);
                
                $total_outstanding += max(0, $schedule_outstanding);

                if ($schedule->due_date < date('Y-m-d') && $schedule_outstanding > 0) {
                    $total_overdue += $schedule_outstanding;
                }
            }
        }

        $par_ratio = $total_outstanding > 0 ? ($total_overdue / $total_outstanding) * 100 : 0;

        if ($par_ratio > 10) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Critical PAR Alert',
                'message' => 'Portfolio at Risk is ' . number_format($par_ratio, 2) . '% - Immediate action required',
                'action' => 'Review overdue loans and collection strategies',
                'priority' => 'high'
            ];
        } elseif ($par_ratio > 5) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-circle',
                'title' => 'High PAR Warning',
                'message' => 'Portfolio at Risk is ' . number_format($par_ratio, 2) . '% - Monitor closely',
                'action' => 'Implement preventive collection measures',
                'priority' => 'medium'
            ];
        }

        // Check for low cash flow
        $last_month_start = Carbon::now()->subMonth()->startOfMonth();
        $last_month_end = Carbon::now()->subMonth()->endOfMonth();

        $monthly_inflow = LoanTransaction::where('reversed', 0)
            ->whereIn('loan_transaction_type_id', [2, 5, 8])
            ->whereBetween('submitted_on', [$last_month_start, $last_month_end])
            ->sum('amount');

        $monthly_outflow = LoanTransaction::where('reversed', 0)
            ->where('loan_transaction_type_id', 1)
            ->whereBetween('submitted_on', [$last_month_start, $last_month_end])
            ->sum('amount');

        $net_cash_flow = $monthly_inflow - $monthly_outflow;

        if ($net_cash_flow < 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-chart-line-down',
                'title' => 'Negative Cash Flow',
                'message' => 'Last month cash flow: ' . number_format($net_cash_flow, 2),
                'action' => 'Review disbursement policies and collection efficiency',
                'priority' => 'medium'
            ];
        }

        // Check for loans due this week
        $week_start = Carbon::now()->startOfWeek();
        $week_end = Carbon::now()->endOfWeek();

        $loans_due_this_week = DB::table('loan_repayment_schedules')
            ->join('loans', 'loan_repayment_schedules.loan_id', '=', 'loans.id')
            ->where('loans.status', 'active')
            ->whereBetween('loan_repayment_schedules.due_date', [$week_start, $week_end])
            ->whereRaw('(loan_repayment_schedules.principal + loan_repayment_schedules.interest + loan_repayment_schedules.fees + loan_repayment_schedules.penalties) > 
                       (loan_repayment_schedules.principal_repaid_derived + loan_repayment_schedules.interest_repaid_derived + loan_repayment_schedules.fees_repaid_derived + loan_repayment_schedules.penalties_repaid_derived)')
            ->count();

        if ($loans_due_this_week > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fas fa-calendar-check',
                'title' => 'Upcoming Payments',
                'message' => $loans_due_this_week . ' loan payments due this week',
                'action' => 'Send payment reminders to clients',
                'priority' => 'low'
            ];
        }

        // Check for high expense ratio
        $monthly_income = Income::whereBetween('date', [$last_month_start, $last_month_end])->sum('amount');
        $monthly_expenses = Expense::whereBetween('date', [$last_month_start, $last_month_end])->sum('amount');

        if ($monthly_income > 0) {
            $expense_ratio = ($monthly_expenses / $monthly_income) * 100;
            if ($expense_ratio > 80) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'fas fa-money-bill-wave',
                    'title' => 'High Expense Ratio',
                    'message' => 'Expenses are ' . number_format($expense_ratio, 1) . '% of income',
                    'action' => 'Review and optimize operational costs',
                    'priority' => 'medium'
                ];
            }
        }

        // Check for inactive clients
        $inactive_clients = DB::table('clients')
            ->leftJoin('loans', 'clients.id', '=', 'loans.client_id')
            ->leftJoin('savings', 'clients.id', '=', 'savings.client_id')
            ->where(function($query) {
                $query->whereNull('loans.id')
                      ->orWhere('loans.status', '!=', 'active');
            })
            ->where(function($query) {
                $query->whereNull('savings.id')
                      ->orWhere('savings.status', '!=', 'active');
            })
            ->where('clients.created_at', '<', Carbon::now()->subMonths(3))
            ->count();

        if ($inactive_clients > 10) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fas fa-user-clock',
                'title' => 'Inactive Clients',
                'message' => $inactive_clients . ' clients have been inactive for 3+ months',
                'action' => 'Launch client re-engagement campaign',
                'priority' => 'low'
            ];
        }

        // Sort alerts by priority
        usort($alerts, function($a, $b) {
            $priority_order = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priority_order[$b['priority']] - $priority_order[$a['priority']];
        });

        return theme_view('dashboard::widgets.financial_alerts', [
            'config' => $this->config,
            'alerts' => $alerts,
            'total_alerts' => count($alerts),
            'high_priority_alerts' => count(array_filter($alerts, function($alert) {
                return $alert['priority'] === 'high';
            }))
        ]);
    }
}
