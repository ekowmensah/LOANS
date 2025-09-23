<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\Loan;
use Modules\Client\Entities\Client;
use Carbon\Carbon;

class PortfolioHealthMetrics extends AbstractWidget
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

        // Get all active loans
        $active_loans = Loan::where('status', 'active')
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('disbursed_on_date', [$start_date, $end_date]);
            })
            ->with('repayment_schedules')
            ->get();

        $total_portfolio_value = 0;
        $total_outstanding = 0;
        $total_overdue = 0;
        $loans_at_risk_count = 0;
        $total_loans_count = $active_loans->count();

        foreach ($active_loans as $loan) {
            $loan_outstanding = 0;
            $loan_overdue = 0;
            $is_at_risk = false;

            foreach ($loan->repayment_schedules as $schedule) {
                $schedule_outstanding = ($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalties) -
                                      ($schedule->principal_repaid_derived + $schedule->interest_repaid_derived + 
                                       $schedule->fees_repaid_derived + $schedule->penalties_repaid_derived);
                
                $loan_outstanding += max(0, $schedule_outstanding);

                // Check if overdue
                if ($schedule->due_date < date('Y-m-d') && $schedule_outstanding > 0) {
                    $loan_overdue += $schedule_outstanding;
                    $is_at_risk = true;
                }
            }

            $total_portfolio_value += $loan->principal;
            $total_outstanding += $loan_outstanding;
            $total_overdue += $loan_overdue;
            
            if ($is_at_risk) {
                $loans_at_risk_count++;
            }
        }

        // Calculate key metrics
        $par_30 = $total_outstanding > 0 ? ($total_overdue / $total_outstanding) * 100 : 0;
        $portfolio_yield = $total_portfolio_value > 0 ? (($total_outstanding - $total_portfolio_value) / $total_portfolio_value) * 100 : 0;
        $default_rate = $total_loans_count > 0 ? ($loans_at_risk_count / $total_loans_count) * 100 : 0;

        // Collection efficiency
        $total_due_this_month = 0;
        $total_collected_this_month = 0;
        $month_start = Carbon::now()->startOfMonth();
        $month_end = Carbon::now()->endOfMonth();

        foreach ($active_loans as $loan) {
            foreach ($loan->repayment_schedules as $schedule) {
                if ($schedule->due_date >= $month_start && $schedule->due_date <= $month_end) {
                    $total_due_this_month += $schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalties;
                    $total_collected_this_month += $schedule->principal_repaid_derived + $schedule->interest_repaid_derived + 
                                                  $schedule->fees_repaid_derived + $schedule->penalties_repaid_derived;
                }
            }
        }

        $collection_efficiency = $total_due_this_month > 0 ? ($total_collected_this_month / $total_due_this_month) * 100 : 0;

        // Client metrics
        $total_clients = Client::count();
        $active_borrowers = Loan::where('status', 'active')->distinct('client_id')->count('client_id');
        $client_retention_rate = $total_clients > 0 ? ($active_borrowers / $total_clients) * 100 : 0;

        return theme_view('dashboard::widgets.portfolio_health_metrics', [
            'config' => $this->config,
            'total_portfolio_value' => $total_portfolio_value,
            'total_outstanding' => $total_outstanding,
            'total_overdue' => $total_overdue,
            'par_30' => $par_30,
            'portfolio_yield' => $portfolio_yield,
            'default_rate' => $default_rate,
            'collection_efficiency' => $collection_efficiency,
            'total_loans_count' => $total_loans_count,
            'loans_at_risk_count' => $loans_at_risk_count,
            'total_clients' => $total_clients,
            'active_borrowers' => $active_borrowers,
            'client_retention_rate' => $client_retention_rate,
        ]);
    }
}
