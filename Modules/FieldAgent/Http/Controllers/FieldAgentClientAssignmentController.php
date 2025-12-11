<?php

namespace Modules\FieldAgent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\FieldAgent\Entities\FieldAgentClientAssignment;
use Modules\Client\Entities\Client;
use Yajra\DataTables\Facades\DataTables;

class FieldAgentClientAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:field_agent.assignments.index'])->only(['index', 'get_assignments']);
        $this->middleware(['permission:field_agent.assignments.create'])->only(['create', 'store']);
        $this->middleware(['permission:field_agent.assignments.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:field_agent.assignments.destroy'])->only(['destroy']);
    }

    /**
     * Display a listing of assignments
     */
    public function index()
    {
        return view('fieldagent::assignments.index');
    }

    /**
     * Get assignments data for DataTables
     */
    public function get_assignments(Request $request)
    {
        $assignments = FieldAgentClientAssignment::with(['fieldAgent.user', 'client', 'assignedBy'])
            ->select('field_agent_client_assignments.*');

        return DataTables::of($assignments)
            ->addColumn('field_agent_name', function ($assignment) {
                return $assignment->fieldAgent ? $assignment->fieldAgent->full_name : 'N/A';
            })
            ->addColumn('agent_code', function ($assignment) {
                return $assignment->fieldAgent ? $assignment->fieldAgent->agent_code : 'N/A';
            })
            ->addColumn('client_name', function ($assignment) {
                return $assignment->client ? $assignment->client->name : 'N/A';
            })
            ->addColumn('client_id_number', function ($assignment) {
                return $assignment->client ? '#' . $assignment->client->id : 'N/A';
            })
            ->addColumn('assigned_by_name', function ($assignment) {
                return $assignment->assignedBy ? $assignment->assignedBy->first_name . ' ' . $assignment->assignedBy->last_name : 'System';
            })
            ->addColumn('status_badge', function ($assignment) {
                if ($assignment->status === 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-secondary">Inactive</span>';
                }
            })
            ->addColumn('action', function ($assignment) {
                $actions = '';
                
                if ($assignment->status === 'active') {
                    $actions .= '<a href="' . url('field_agent/assignments/' . $assignment->id . '/deactivate') . '" class="btn btn-sm btn-warning confirm-action" data-message="Are you sure you want to deactivate this assignment?"><i class="fa fa-ban"></i> Deactivate</a> ';
                }
                
                $actions .= '<a href="' . url('field_agent/assignments/' . $assignment->id . '/edit') . '" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a> ';
                $actions .= '<a href="' . url('field_agent/assignments/' . $assignment->id . '/delete') . '" class="btn btn-sm btn-danger confirm-action" data-message="Are you sure you want to delete this assignment?"><i class="fa fa-trash"></i> Delete</a>';
                
                return $actions;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create()
    {
        $fieldAgents = FieldAgent::with('user')->where('status', 'active')->get();
        $clients = Client::where('status', 'active')->get();
        
        return view('fieldagent::assignments.create', compact('fieldAgents', 'clients'));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_agent_id' => 'required|exists:field_agents,id',
            'client_id' => 'required|exists:clients,id',
            'assigned_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Check if client already has an active assignment
            $existingAssignment = FieldAgentClientAssignment::where('client_id', $request->client_id)
                ->where('status', 'active')
                ->first();

            if ($existingAssignment) {
                // Deactivate the existing assignment
                $existingAssignment->deactivate();
            }

            // Create new assignment
            $assignment = FieldAgentClientAssignment::create([
                'field_agent_id' => $request->field_agent_id,
                'client_id' => $request->client_id,
                'assigned_by_user_id' => Auth::id(),
                'assigned_date' => $request->assigned_date,
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            DB::commit();

            flash('Client assigned to field agent successfully.')->success();
            return redirect()->route('field_agent.assignments.index');
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Error assigning client: ' . $e->getMessage())->error();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing an assignment
     */
    public function edit($id)
    {
        $assignment = FieldAgentClientAssignment::findOrFail($id);
        $fieldAgents = FieldAgent::with('user')->where('status', 'active')->get();
        $clients = Client::where('status', 'active')->get();
        
        return view('fieldagent::assignments.edit', compact('assignment', 'fieldAgents', 'clients'));
    }

    /**
     * Update the specified assignment
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'field_agent_id' => 'required|exists:field_agents,id',
            'client_id' => 'required|exists:clients,id',
            'assigned_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $assignment = FieldAgentClientAssignment::findOrFail($id);
            
            $assignment->update([
                'field_agent_id' => $request->field_agent_id,
                'client_id' => $request->client_id,
                'assigned_date' => $request->assigned_date,
                'notes' => $request->notes,
            ]);

            flash('Assignment updated successfully.')->success();
            return redirect()->route('field_agent.assignments.index');
        } catch (\Exception $e) {
            flash('Error updating assignment: ' . $e->getMessage())->error();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Deactivate an assignment
     */
    public function deactivate($id)
    {
        try {
            $assignment = FieldAgentClientAssignment::findOrFail($id);
            $assignment->deactivate();

            flash('Assignment deactivated successfully.')->success();
            return redirect()->route('field_agent.assignments.index');
        } catch (\Exception $e) {
            flash('Error deactivating assignment: ' . $e->getMessage())->error();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified assignment
     */
    public function destroy($id)
    {
        try {
            $assignment = FieldAgentClientAssignment::findOrFail($id);
            $assignment->delete();

            flash('Assignment deleted successfully.')->success();
            return redirect()->route('field_agent.assignments.index');
        } catch (\Exception $e) {
            flash('Error deleting assignment: ' . $e->getMessage())->error();
            return redirect()->back();
        }
    }

    /**
     * Get clients assigned to a specific field agent
     */
    public function getAgentClients($agentId)
    {
        $fieldAgent = FieldAgent::findOrFail($agentId);
        $clients = $fieldAgent->assignedClients;

        return response()->json([
            'success' => true,
            'clients' => $clients
        ]);
    }

    /**
     * Bulk assign clients to a field agent
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'field_agent_id' => 'required|exists:field_agents,id',
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'assigned_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errors = [];

            foreach ($request->client_ids as $clientId) {
                try {
                    // Check if client already has an active assignment
                    $existingAssignment = FieldAgentClientAssignment::where('client_id', $clientId)
                        ->where('status', 'active')
                        ->first();

                    if ($existingAssignment) {
                        $existingAssignment->deactivate();
                    }

                    // Create new assignment
                    FieldAgentClientAssignment::create([
                        'field_agent_id' => $request->field_agent_id,
                        'client_id' => $clientId,
                        'assigned_by_user_id' => Auth::id(),
                        'assigned_date' => $request->assigned_date,
                        'status' => 'active',
                        'notes' => $request->notes ?? null,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Client ID {$clientId}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "{$successCount} client(s) assigned successfully.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            flash($message)->success();
            return redirect()->route('field_agent.assignments.index');
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Error in bulk assignment: ' . $e->getMessage())->error();
            return redirect()->back()->withInput();
        }
    }
}
