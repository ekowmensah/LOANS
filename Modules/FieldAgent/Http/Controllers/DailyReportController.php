<?php

namespace Modules\FieldAgent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\FieldAgent\Entities\FieldAgentDailyReport;
use Modules\FieldAgent\Entities\FieldCollection;
use Yajra\DataTables\Facades\DataTables;

class DailyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:field_agent.reports.index'])->only(['index', 'get_reports']);
        $this->middleware(['permission:field_agent.reports.create'])->only(['create', 'store']);
        $this->middleware(['permission:field_agent.reports.view'])->only(['show']);
        $this->middleware(['permission:field_agent.reports.approve'])->only(['approve', 'reject']);
    }

    /**
     * Display a listing of daily reports
     */
    public function index()
    {
        $fieldAgents = FieldAgent::active()->get();
        return theme_view('fieldagent::daily_report.index', compact('fieldAgents'));
    }

    /**
     * Get daily reports data for DataTables
     */
    public function get_reports(Request $request)
    {
        $query = FieldAgentDailyReport::with(['fieldAgent.user', 'approvedBy']);

        // Filter by field agent
        if ($request->field_agent_id) {
            $query->where('field_agent_id', $request->field_agent_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('report_date', [$request->start_date, $request->end_date]);
        }

        // If user is a field agent, show only their reports
        $user = Auth::user();
        if ($user->can('field_agent.reports.view_own') && !$user->can('field_agent.reports.index')) {
            $fieldAgent = FieldAgent::where('user_id', $user->id)->first();
            if ($fieldAgent) {
                $query->where('field_agent_id', $fieldAgent->id);
            }
        }

        return DataTables::of($query)
            ->editColumn('report_date', function ($data) {
                return '<a href="' . url('field-agent/daily-report/' . $data->id . '/show') . '">' . $data->report_date->format('Y-m-d') . '</a>';
            })
            ->editColumn('field_agent', function ($data) {
                return $data->fieldAgent ? $data->fieldAgent->full_name : 'N/A';
            })
            ->editColumn('total_collections', function ($data) {
                return $data->total_collections;
            })
            ->editColumn('total_amount_collected', function ($data) {
                return number_format($data->total_amount_collected, 2);
            })
            ->editColumn('cash_deposited', function ($data) {
                return number_format($data->cash_deposited_to_branch, 2);
            })
            ->editColumn('variance', function ($data) {
                $variance = $data->variance;
                $color = $variance == 0 ? 'success' : 'danger';
                return '<span class="badge badge-' . $color . '">' . number_format($variance, 2) . '</span>';
            })
            ->editColumn('status', function ($data) {
                return $data->status_badge;
            })
            ->addColumn('action', function ($data) {
                $actions = '<div class="btn-group">';
                $actions .= '<a href="' . url('field-agent/daily-report/' . $data->id . '/show') . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                
                if ($data->canBeApproved() && Auth::user()->can('field_agent.reports.approve')) {
                    $actions .= '<a href="' . url('field-agent/daily-report/' . $data->id . '/approve') . '" class="btn btn-success btn-sm confirm"><i class="fa fa-check"></i> Approve</a>';
                    $actions .= '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal' . $data->id . '"><i class="fa fa-times"></i> Reject</button>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['report_date', 'variance', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new daily report
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user is a field agent
        $fieldAgent = FieldAgent::where('user_id', $user->id)->first();

        // If not a field agent, allow admin to select field agent
        if (!$fieldAgent) {
            // Get all active field agents for admin to choose
            $fieldAgents = FieldAgent::with('user')->where('status', 'active')->get();
            
            return theme_view('fieldagent::daily_report.create', compact('fieldAgents'));
        }

        // Check if report already exists for today
        $today = now()->format('Y-m-d');
        $existingReport = FieldAgentDailyReport::where('field_agent_id', $fieldAgent->id)
            ->whereDate('report_date', $today)
            ->first();

        if ($existingReport) {
            flash('Report for today already exists. You can edit it below.')->info();
            return redirect('field-agent/daily-report/' . $existingReport->id . '/show');
        }

        // Get today's collections
        $collections = FieldCollection::where('field_agent_id', $fieldAgent->id)
            ->whereDate('collection_date', $today)
            ->where('status', '!=', 'rejected')
            ->get();

        $totalCollections = $collections->count();
        $totalAmount = $collections->sum('amount');
        $totalClients = $collections->pluck('client_id')->unique()->count();

        return theme_view('fieldagent::daily_report.create', compact(
            'fieldAgent',
            'collections',
            'totalCollections',
            'totalAmount',
            'totalClients'
        ));
    }

    /**
     * Store a newly created daily report
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_agent_id' => 'required|exists:field_agents,id',
            'report_date' => 'required|date',
            'opening_cash_balance' => 'required|numeric|min:0',
            'closing_cash_balance' => 'required|numeric|min:0',
            'cash_deposited_to_branch' => 'required|numeric|min:0',
            'total_clients_visited' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if report already exists
        $existing = FieldAgentDailyReport::where('field_agent_id', $request->field_agent_id)
            ->whereDate('report_date', $request->report_date)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Report for this date already exists');
        }

        $report = new FieldAgentDailyReport($request->all());
        $report->calculateTotalsFromCollections();
        $report->status = 'pending';
        $report->save();

        return redirect('field-agent/daily-report/' . $report->id . '/show')
            ->with('success', 'Daily report created successfully');
    }

    /**
     * Display the specified daily report
     */
    public function show($id)
    {
        $report = FieldAgentDailyReport::with([
            'fieldAgent.user',
            'depositedBy',
            'approvedBy',
            'collections.client'
        ])->findOrFail($id);

        // Get collections for this report
        $collections = FieldCollection::where('field_agent_id', $report->field_agent_id)
            ->whereDate('collection_date', $report->report_date)
            ->with('client')
            ->get();

        return theme_view('fieldagent::daily_report.show', compact('report', 'collections'));
    }

    /**
     * Submit a daily report
     */
    public function submit($id)
    {
        $report = FieldAgentDailyReport::findOrFail($id);

        if (!$report->canBeSubmitted()) {
            return redirect()->back()->with('error', 'This report cannot be submitted');
        }

        $report->submit();

        return redirect()->back()->with('success', 'Report submitted for approval');
    }

    /**
     * Approve a daily report
     */
    public function approve($id)
    {
        $report = FieldAgentDailyReport::findOrFail($id);

        if (!$report->canBeApproved()) {
            return redirect()->back()->with('error', 'This report cannot be approved');
        }

        $report->approve(Auth::id());

        return redirect()->back()->with('success', 'Report approved successfully');
    }

    /**
     * Reject a daily report
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $report = FieldAgentDailyReport::findOrFail($id);

        if (!$report->canBeApproved()) {
            return redirect()->back()->with('error', 'This report cannot be rejected');
        }

        $report->reject(Auth::id(), $request->rejection_reason);

        return redirect()->back()->with('success', 'Report rejected');
    }

    /**
     * Record cash deposit to branch
     */
    public function record_deposit(Request $request, $id)
    {
        $request->validate([
            'deposit_receipt_number' => 'required|string',
        ]);

        $report = FieldAgentDailyReport::findOrFail($id);

        $report->update([
            'deposited_by_user_id' => Auth::id(),
            'deposit_receipt_number' => $request->deposit_receipt_number,
        ]);

        return redirect()->back()->with('success', 'Deposit recorded successfully');
    }
}
