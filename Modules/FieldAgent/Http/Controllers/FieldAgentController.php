<?php

namespace Modules\FieldAgent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\Branch\Entities\Branch;
use Modules\User\Entities\User;
use Yajra\DataTables\Facades\DataTables;

class FieldAgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:field_agent.agents.index'])->only(['index', 'get_agents']);
        $this->middleware(['permission:field_agent.agents.create'])->only(['create', 'store']);
        $this->middleware(['permission:field_agent.agents.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:field_agent.agents.destroy'])->only(['destroy']);
        $this->middleware(['permission:field_agent.agents.view'])->only(['show']);
    }

    /**
     * Display a listing of field agents
     */
    public function index()
    {
        return theme_view('fieldagent::agent.index');
    }

    /**
     * Get field agents data for DataTables
     */
    public function get_agents(Request $request)
    {
        $query = FieldAgent::with(['user', 'branch']);

        // Filter by branch if specified
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by status if specified
        if ($request->status) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->editColumn('agent_code', function ($data) {
                return '<a href="' . url('field-agent/agent/' . $data->id . '/show') . '">' . $data->agent_code . '</a>';
            })
            ->editColumn('user', function ($data) {
                return $data->full_name;
            })
            ->editColumn('branch', function ($data) {
                return $data->branch ? $data->branch->name : 'N/A';
            })
            ->editColumn('commission_rate', function ($data) {
                return $data->commission_rate . '%';
            })
            ->editColumn('target_amount', function ($data) {
                return number_format($data->target_amount, 2);
            })
            ->editColumn('status', function ($data) {
                $badges = [
                    'active' => '<span class="badge badge-success">Active</span>',
                    'suspended' => '<span class="badge badge-warning">Suspended</span>',
                    'inactive' => '<span class="badge badge-danger">Inactive</span>',
                ];
                return $badges[$data->status] ?? '';
            })
            ->editColumn('performance', function ($data) {
                $percentage = $data->performance_percentage;
                $color = $percentage >= 100 ? 'success' : ($percentage >= 75 ? 'warning' : 'danger');
                return '<div class="progress">
                    <div class="progress-bar bg-' . $color . '" style="width: ' . min($percentage, 100) . '%">
                        ' . number_format($percentage, 1) . '%
                    </div>
                </div>';
            })
            ->addColumn('action', function ($data) {
                $actions = '<div class="btn-group">';
                $actions .= '<a href="' . url('field-agent/agent/' . $data->id . '/show') . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                
                if (Auth::user()->can('field_agent.agents.edit')) {
                    $actions .= '<a href="' . url('field-agent/agent/' . $data->id . '/edit') . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                }
                
                if (Auth::user()->can('field_agent.agents.destroy')) {
                    $actions .= '<a href="' . url('field-agent/agent/' . $data->id . '/destroy') . '" class="btn btn-danger btn-sm confirm"><i class="fa fa-trash"></i></a>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['agent_code', 'status', 'performance', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new field agent
     */
    public function create()
    {
        $branches = Branch::all();
        $users = User::whereDoesntHave('fieldAgent')
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'client');
            })->get();

        return theme_view('fieldagent::agent.create', compact('branches', 'users'));
    }

    /**
     * Store a newly created field agent
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:field_agents,user_id',
            'agent_code' => 'required|unique:field_agents,agent_code',
            'branch_id' => 'required|exists:branches,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'target_amount' => 'nullable|numeric|min:0',
            'phone_number' => 'nullable|string',
            'national_id' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');
        $data['status'] = 'active';

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/field_agents'), $filename);
            $data['photo'] = 'uploads/field_agents/' . $filename;
        }

        FieldAgent::create($data);

        return redirect('field-agent/agent')->with('success', 'Field agent created successfully');
    }

    /**
     * Display the specified field agent
     */
    public function show($id)
    {
        $agent = FieldAgent::with(['user', 'branch', 'collections', 'dailyReports'])->findOrFail($id);
        
        // Get current month statistics
        $currentMonth = now()->format('Y-m');
        $monthlyCollections = $agent->collections()
            ->whereRaw("DATE_FORMAT(collection_date, '%Y-%m') = ?", [$currentMonth])
            ->where('status', '!=', 'rejected')
            ->get();

        $stats = [
            'total_collections' => $monthlyCollections->count(),
            'total_amount' => $monthlyCollections->sum('amount'),
            'pending_collections' => $agent->pendingCollections()->count(),
            'verified_collections' => $agent->verifiedCollections()->count(),
            'performance_percentage' => $agent->performance_percentage,
            'target_amount' => $agent->target_amount,
        ];

        // Recent collections
        $recentCollections = $agent->collections()
            ->with('client')
            ->orderBy('collection_date', 'desc')
            ->limit(10)
            ->get();

        // Recent reports
        $recentReports = $agent->dailyReports()
            ->orderBy('report_date', 'desc')
            ->limit(10)
            ->get();

        return theme_view('fieldagent::agent.show', compact('agent', 'stats', 'recentCollections', 'recentReports'));
    }

    /**
     * Show the form for editing the specified field agent
     */
    public function edit($id)
    {
        $agent = FieldAgent::findOrFail($id);
        $branches = Branch::all();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'client');
        })->get();

        return theme_view('fieldagent::agent.edit', compact('agent', 'branches', 'users'));
    }

    /**
     * Update the specified field agent
     */
    public function update(Request $request, $id)
    {
        $agent = FieldAgent::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id|unique:field_agents,user_id,' . $id,
            'agent_code' => 'required|unique:field_agents,agent_code,' . $id,
            'branch_id' => 'required|exists:branches,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'target_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,suspended,inactive',
            'phone_number' => 'nullable|string',
            'national_id' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($agent->photo && file_exists(public_path($agent->photo))) {
                unlink(public_path($agent->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/field_agents'), $filename);
            $data['photo'] = 'uploads/field_agents/' . $filename;
        }

        $agent->update($data);

        return redirect('field-agent/agent/' . $id . '/show')->with('success', 'Field agent updated successfully');
    }

    /**
     * Remove the specified field agent
     */
    public function destroy($id)
    {
        $agent = FieldAgent::findOrFail($id);

        // Check if agent has collections
        if ($agent->collections()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete field agent with existing collections');
        }

        // Delete photo if exists
        if ($agent->photo && file_exists(public_path($agent->photo))) {
            unlink(public_path($agent->photo));
        }

        $agent->delete();

        return redirect('field-agent/agent')->with('success', 'Field agent deleted successfully');
    }
}
