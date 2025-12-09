<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchUser;
use Modules\Core\Entities\Currency;
use Modules\CustomField\Entities\CustomField;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    /**
     * BranchController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:branch.branches.index'])->only(['index', 'show']);
        $this->middleware(['permission:branch.branches.create'])->only(['create', 'store']);
        $this->middleware(['permission:branch.branches.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:branch.branches.destroy'])->only(['destroy']);
        $this->middleware(['permission:branch.branches.assign_user'])->only(['assign_user']);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?: 20;
        $orderBy = $request->order_by;
        $orderByDir = $request->order_by_dir;
        $search = $request->s;
        
        $data = Branch::leftJoin('clients', 'clients.branch_id', '=', 'branches.id')
            ->leftJoin('groups', 'groups.branch_id', '=', 'branches.id')
            ->leftJoin('loans', 'loans.branch_id', '=', 'branches.id')
            ->leftJoin('savings', function($join) {
                $join->on('savings.branch_id', '=', 'branches.id')
                     ->where('savings.status', '=', 'active');
            })
            ->when($orderBy, function (Builder $query) use ($orderBy, $orderByDir) {
                $query->orderBy('branches.' . $orderBy, $orderByDir);
            })
            ->when($search, function (Builder $query) use ($search) {
                $query->where('branches.name', 'like', "%$search%");
            })
            ->selectRaw('
                branches.id,
                branches.name,
                branches.open_date,
                COUNT(DISTINCT clients.id) as total_clients,
                COUNT(DISTINCT groups.id) as total_groups,
                COUNT(DISTINCT CASE WHEN loans.client_type = "group" THEN loans.id END) as total_group_loans,
                COUNT(DISTINCT CASE WHEN loans.client_type = "client" THEN loans.id END) as total_individual_loans,
                COALESCE(SUM(DISTINCT savings.balance_derived), 0) as total_savings,
                COALESCE(SUM(loans.principal), 0) as loan_disbursed,
                COALESCE(SUM(loans.principal_repaid_derived), 0) as loan_paid,
                COALESCE(SUM(loans.principal) - SUM(loans.principal_repaid_derived), 0) as loan_outstanding
            ')
            ->groupBy('branches.id', 'branches.name', 'branches.open_date')
            ->paginate($perPage)
            ->appends($request->input());
            
        return theme_view('branch::branch.index', compact('data'));
    }

    public function get_branches(Request $request)
    {
        $query = Branch::query();
        return DataTables::of($query)->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-navicon"></i></button> <ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Auth::user()->hasPermissionTo('branch.branches.edit')) {
                $action .= '<li><a href="' . url('branch/' . $data->id . '/show') . '" class="">' . trans_choice('core::general.detail', 2) . '</a></li>';
            }
            if (Auth::user()->hasPermissionTo('branch.branches.edit')) {
                $action .= '<li><a href="' . url('branch/' . $data->id . '/edit') . '" class="">' . trans_choice('core::general.edit', 2) . '</a></li>';
            }
            if (Auth::user()->hasPermissionTo('branch.branches.destroy')) {
                $action .= '<li><a href="' . url('branch/' . $data->id . '/destroy') . '" class="confirm">' . trans_choice('core::general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></li></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('branch/' . $data->id . '/show') . '">' . $data->id . '</a>';

        })->editColumn('name', function ($data) {
            return '<a href="' . url('branch/' . $data->id . '/show') . '">' . $data->name . '</a>';

        })->rawColumns(['id', 'name', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $custom_fields = CustomField::where('category', 'add_branch')->where('active', 1)->get();
        return theme_view('branch::branch.create', compact('custom_fields'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'active' => ['required'],
        ]);
        $branch = new Branch();
        $branch->name = $request->name;
        $branch->open_date = $request->open_date;
        $branch->active = $request->active;
        $branch->notes = $request->notes;
        $branch->save();
        custom_fields_save_form('add_branch', $request, $branch->id);
        activity()->on($branch)
            ->withProperties(['id' => $branch->id])
            ->log('Create Branch');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('branch');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $branch = Branch::with('users')->find($id);
        
        if (!$branch) {
            abort(404, 'Branch not found');
        }
        
        $custom_fields = CustomField::where('category', 'add_branch')->where('active', 1)->get();
        
        // Client Statistics
        $total_clients = \DB::table('clients')->where('branch_id', $id)->count();
        $active_clients = \DB::table('clients')->where('branch_id', $id)->where('status', 'active')->count();
        $inactive_clients = \DB::table('clients')->where('branch_id', $id)->where('status', 'inactive')->count();
        
        // Group Statistics
        $total_groups = \DB::table('groups')->where('branch_id', $id)->count();
        $active_groups = \DB::table('groups')->where('branch_id', $id)->where('status', 'active')->count();
        
        // Loan Statistics
        $loan_stats = \DB::table('loans')
            ->where('branch_id', $id)
            ->selectRaw('
                COUNT(*) as total_loans,
                COUNT(CASE WHEN status = "active" THEN 1 END) as active_loans,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_loans,
                COUNT(CASE WHEN status = "approved" THEN 1 END) as approved_loans,
                COUNT(CASE WHEN status = "disbursed" THEN 1 END) as disbursed_loans,
                COUNT(CASE WHEN status = "closed" THEN 1 END) as closed_loans,
                COUNT(CASE WHEN status = "written_off" THEN 1 END) as written_off_loans,
                COUNT(CASE WHEN status = "rescheduled" THEN 1 END) as rescheduled_loans,
                COUNT(CASE WHEN status = "overpaid" THEN 1 END) as overpaid_loans,
                COALESCE(SUM(principal), 0) as total_principal,
                COALESCE(SUM(principal_disbursed_derived), 0) as principal_disbursed,
                COALESCE(SUM(principal_repaid_derived), 0) as principal_repaid,
                COALESCE(SUM(principal_written_off_derived), 0) as principal_written_off,
                COALESCE(SUM(principal_disbursed_derived - principal_repaid_derived - principal_written_off_derived), 0) as principal_outstanding,
                COALESCE(SUM(interest_disbursed_derived), 0) as interest_disbursed,
                COALESCE(SUM(interest_repaid_derived), 0) as interest_repaid,
                COALESCE(SUM(interest_written_off_derived), 0) as interest_written_off,
                COALESCE(SUM(interest_waived_derived), 0) as interest_waived,
                COALESCE(SUM(interest_disbursed_derived - interest_repaid_derived - interest_written_off_derived - interest_waived_derived), 0) as interest_outstanding,
                COALESCE(SUM(fees_disbursed_derived), 0) as fees_disbursed,
                COALESCE(SUM(fees_repaid_derived), 0) as fees_repaid,
                COALESCE(SUM(fees_written_off_derived), 0) as fees_written_off,
                COALESCE(SUM(fees_waived_derived), 0) as fees_waived,
                COALESCE(SUM(penalties_disbursed_derived), 0) as penalties_disbursed,
                COALESCE(SUM(penalties_repaid_derived), 0) as penalties_repaid,
                COALESCE(SUM(penalties_written_off_derived), 0) as penalties_written_off,
                COALESCE(SUM(penalties_waived_derived), 0) as penalties_waived
            ')
            ->first();
        
        // Ensure loan_stats is not null
        if (!$loan_stats) {
            $loan_stats = (object)[
                'total_loans' => 0,
                'active_loans' => 0,
                'pending_loans' => 0,
                'approved_loans' => 0,
                'disbursed_loans' => 0,
                'closed_loans' => 0,
                'written_off_loans' => 0,
                'rescheduled_loans' => 0,
                'overpaid_loans' => 0,
                'total_principal' => 0,
                'principal_disbursed' => 0,
                'principal_repaid' => 0,
                'principal_written_off' => 0,
                'principal_outstanding' => 0,
                'interest_disbursed' => 0,
                'interest_repaid' => 0,
                'interest_written_off' => 0,
                'interest_waived' => 0,
                'interest_outstanding' => 0,
                'fees_disbursed' => 0,
                'fees_repaid' => 0,
                'fees_written_off' => 0,
                'fees_waived' => 0,
                'penalties_disbursed' => 0,
                'penalties_repaid' => 0,
                'penalties_written_off' => 0,
                'penalties_waived' => 0,
            ];
        }
        
        // Savings Statistics
        $savings_stats = \DB::table('savings')
            ->where('branch_id', $id)
            ->selectRaw('
                COUNT(*) as total_savings,
                COUNT(CASE WHEN status = "active" THEN 1 END) as active_savings,
                COUNT(CASE WHEN status = "inactive" THEN 1 END) as inactive_savings,
                COUNT(CASE WHEN status = "closed" THEN 1 END) as closed_savings,
                COUNT(CASE WHEN status = "withdrawn" THEN 1 END) as withdrawn_savings,
                COALESCE(SUM(CASE WHEN status = "active" THEN balance_derived ELSE 0 END), 0) as total_balance,
                COALESCE(SUM(total_deposits_derived), 0) as total_deposits,
                COALESCE(SUM(total_withdrawals_derived), 0) as total_withdrawals,
                COALESCE(SUM(total_interest_posted_derived), 0) as total_interest_posted
            ')
            ->first();
        
        // Ensure savings_stats is not null
        if (!$savings_stats) {
            $savings_stats = (object)[
                'total_savings' => 0,
                'active_savings' => 0,
                'inactive_savings' => 0,
                'closed_savings' => 0,
                'withdrawn_savings' => 0,
                'total_balance' => 0,
                'total_deposits' => 0,
                'total_withdrawals' => 0,
                'total_interest_posted' => 0,
            ];
        }
        
        // Loan Portfolio Quality (PAR - Portfolio at Risk)
        $par_stats = \DB::table('loans')
            ->leftJoin('loan_repayment_schedules', 'loan_repayment_schedules.loan_id', '=', 'loans.id')
            ->where('loans.branch_id', $id)
            ->where('loans.status', 'active')
            ->selectRaw('
                COALESCE(SUM(CASE WHEN loan_repayment_schedules.due_date < CURDATE() 
                    AND (loan_repayment_schedules.principal - loan_repayment_schedules.principal_repaid_derived - loan_repayment_schedules.principal_written_off_derived) > 0 
                    THEN (loan_repayment_schedules.principal - loan_repayment_schedules.principal_repaid_derived - loan_repayment_schedules.principal_written_off_derived) 
                    ELSE 0 END), 0) as par_amount,
                COALESCE(SUM(CASE WHEN loan_repayment_schedules.due_date < DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                    AND (loan_repayment_schedules.principal - loan_repayment_schedules.principal_repaid_derived - loan_repayment_schedules.principal_written_off_derived) > 0 
                    THEN (loan_repayment_schedules.principal - loan_repayment_schedules.principal_repaid_derived - loan_repayment_schedules.principal_written_off_derived) 
                    ELSE 0 END), 0) as par_30,
                COALESCE(SUM(CASE WHEN loan_repayment_schedules.due_date < DATE_SUB(CURDATE(), INTERVAL 90 DAY) 
                    AND (loan_repayment_schedules.principal - loan_repayment_schedules.principal_repaid_derived - loan_repayment_schedules.principal_written_off_derived) > 0 
                    THEN (loan_repayment_schedules.principal - loan_repayment_schedules.principal_repaid_derived - loan_repayment_schedules.principal_written_off_derived) 
                    ELSE 0 END), 0) as par_90
            ')
            ->first();
        
        // Ensure par_stats is not null
        if (!$par_stats) {
            $par_stats = (object)[
                'par_amount' => 0,
                'par_30' => 0,
                'par_90' => 0,
            ];
        }
        
        // Recent Loan Applications (Last 30 days)
        $recent_loan_applications = \DB::table('loans')
            ->where('branch_id', $id)
            ->where('created_at', '>=', \DB::raw('DATE_SUB(CURDATE(), INTERVAL 30 DAY)'))
            ->count();
        
        // Recent Loan Disbursements (Last 30 days)
        $recent_disbursements = \DB::table('loans')
            ->where('branch_id', $id)
            ->where('disbursed_on_date', '>=', \DB::raw('DATE_SUB(CURDATE(), INTERVAL 30 DAY)'))
            ->selectRaw('
                COUNT(*) as count,
                COALESCE(SUM(principal_disbursed_derived), 0) as amount
            ')
            ->first();
        
        // Ensure recent_disbursements is not null
        if (!$recent_disbursements) {
            $recent_disbursements = (object)[
                'count' => 0,
                'amount' => 0,
            ];
        }
        
        // Monthly trends (Last 6 months)
        $monthly_trends = \DB::table('loans')
            ->where('branch_id', $id)
            ->where('disbursed_on_date', '>=', \DB::raw('DATE_SUB(CURDATE(), INTERVAL 6 MONTH)'))
            ->selectRaw('
                DATE_FORMAT(disbursed_on_date, "%Y-%m") as month,
                COUNT(*) as loan_count,
                COALESCE(SUM(principal_disbursed_derived), 0) as disbursed_amount
            ')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
        
        return theme_view('branch::branch.show', compact(
            'branch', 
            'custom_fields',
            'total_clients',
            'active_clients',
            'inactive_clients',
            'total_groups',
            'active_groups',
            'loan_stats',
            'savings_stats',
            'par_stats',
            'recent_loan_applications',
            'recent_disbursements',
            'monthly_trends'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $branch = Branch::find($id);
        $custom_fields = CustomField::where('category', 'add_branch')->where('active', 1)->get();
        return theme_view('branch::branch.edit', compact('branch', 'custom_fields'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'active' => ['required'],
        ]);
        $branch = Branch::find($id);
        $branch->name = $request->name;
        $branch->open_date = $request->open_date;
        $branch->active = $request->active;
        $branch->notes = $request->notes;
        $branch->save();
        custom_fields_save_form('add_branch', $request, $branch->id);
        activity()->on($branch)
            ->withProperties(['id' => $branch->id])
            ->log('Update Branch');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('branch');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);
        if ($branch->is_system == 1) {
            \flash(trans_choice("core::general.cannot_delete_system_branch", 1))->error()->important();
            return redirect()->back();
        }
        $branch->delete();
        activity()->on($branch)
            ->withProperties(['id' => $branch->id])
            ->log('Delete Branch');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }

    public function add_user(Request $request, $id)
    {
        if (BranchUser::where('user_id', $request->user_id)->where('branch_id', $id)->get()->count() > 0) {
            Flash::warning(trans_choice("branch::general.user_already_added_to_branch", 1));
            return redirect()->back();
        }
        $branch_user = new BranchUser();
        $branch_user->branch_id = $id;
        $branch_user->user_id = $request->user_id;
        $branch_user->created_by_id = Auth::id();
        $branch_user->save();
        activity()->on($branch_user)
            ->withProperties(['id' => $branch_user->id])
            ->log('Add Branch User');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect()->back();
    }

    public function remove_user($id)
    {
        BranchUser::destroy($id);
        activity()->log('Remove Branch User');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }
}
