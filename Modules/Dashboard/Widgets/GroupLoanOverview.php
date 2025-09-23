<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\Loan;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\Group;

class GroupLoanOverview extends AbstractWidget
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

        // Group loan statistics
        $group_loans = Loan::where('client_type', 'group')
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('disbursed_on_date', [$start_date, $end_date]);
            })
            ->with('repayment_schedules')
            ->get();

        $total_group_loans = $group_loans->count();
        $active_group_loans = $group_loans->where('status', 'active')->count();
        $total_group_portfolio = $group_loans->where('status', 'active')->sum('principal');

        // Group client statistics
        $total_groups = Client::where('client_type_id', function($query) {
            $query->select('id')
                  ->from('client_types')
                  ->where('name', 'Group')
                  ->limit(1);
        })->count();

        $active_groups = Loan::where('client_type', 'group')
            ->where('status', 'active')
            ->distinct('client_id')
            ->count('client_id');

        // Calculate group loan performance
        $total_group_outstanding = 0;
        $total_group_overdue = 0;
        $groups_at_risk = 0;

        foreach ($group_loans->where('status', 'active') as $loan) {
            $loan_outstanding = 0;
            $loan_overdue = 0;
            $is_at_risk = false;

            foreach ($loan->repayment_schedules as $schedule) {
                $schedule_outstanding = ($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalties) -
                                      ($schedule->principal_repaid_derived + $schedule->interest_repaid_derived + 
                                       $schedule->fees_repaid_derived + $schedule->penalties_repaid_derived);
                
                $loan_outstanding += max(0, $schedule_outstanding);

                if ($schedule->due_date < date('Y-m-d') && $schedule_outstanding > 0) {
                    $loan_overdue += $schedule_outstanding;
                    $is_at_risk = true;
                }
            }

            $total_group_outstanding += $loan_outstanding;
            $total_group_overdue += $loan_overdue;
            
            if ($is_at_risk) {
                $groups_at_risk++;
            }
        }

        // Group loan PAR
        $group_par = $total_group_outstanding > 0 ? ($total_group_overdue / $total_group_outstanding) * 100 : 0;

        // Average group size (members per group)
        $total_group_members = DB::table('group_members')->count();
        $avg_group_size = $total_groups > 0 ? $total_group_members / $total_groups : 0;

        // Recent group loan activities
        $recent_group_disbursements = Loan::where('client_type', 'group')
            ->where('status', 'active')
            ->whereBetween('disbursed_on_date', [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')])
            ->count();

        $recent_group_applications = Loan::where('client_type', 'group')
            ->whereIn('status', ['submitted', 'pending'])
            ->whereBetween('created_at', [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')])
            ->count();

        return theme_view('dashboard::widgets.group_loan_overview', [
            'config' => $this->config,
            'total_group_loans' => $total_group_loans,
            'active_group_loans' => $active_group_loans,
            'total_group_portfolio' => $total_group_portfolio,
            'total_groups' => $total_groups,
            'active_groups' => $active_groups,
            'total_group_outstanding' => $total_group_outstanding,
            'total_group_overdue' => $total_group_overdue,
            'group_par' => $group_par,
            'groups_at_risk' => $groups_at_risk,
            'avg_group_size' => $avg_group_size,
            'recent_group_disbursements' => $recent_group_disbursements,
            'recent_group_applications' => $recent_group_applications,
        ]);
    }
}
