<?php

namespace Modules\FieldAgent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FieldAgent\Entities\FieldAgent;
use Modules\Client\Entities\Client;
use Yajra\DataTables\Facades\DataTables;

class ClientFieldAgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:field_agent.assignments.index'])->only(['index', 'get_clients']);
        $this->middleware(['permission:field_agent.assignments.create'])->only(['assign', 'updateAssignment']);
    }

    /**
     * Display clients with their field agent assignments
     */
    public function index()
    {
        $fieldAgents = FieldAgent::with('user')->where('status', 'active')->get();
        return view('fieldagent::client_assignments.index', compact('fieldAgents'));
    }

    /**
     * Get clients data for DataTables
     */
    public function get_clients(Request $request)
    {
        $clients = Client::with(['field_agent.user', 'branch'])
            ->where('status', 'active')
            ->select('clients.*');

        // Filter by field agent if specified
        if ($request->has('field_agent_id') && $request->field_agent_id != '') {
            if ($request->field_agent_id == 'unassigned') {
                $clients->whereNull('field_agent_id');
            } elseif ($request->field_agent_id == 'assigned') {
                $clients->whereNotNull('field_agent_id');
            } else {
                $clients->where('field_agent_id', $request->field_agent_id);
            }
        }

        // Filter by branch if specified
        if ($request->has('branch_id') && $request->branch_id != '') {
            $clients->where('branch_id', $request->branch_id);
        }

        // Search by name, ID, or mobile
        if ($request->has('search_query') && $request->search_query != '') {
            $searchTerm = $request->search_query;
            $clients->where(function($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('mobile', 'like', '%' . $searchTerm . '%')
                      ->orWhere('id', 'like', '%' . $searchTerm . '%');
            });
        }

        return DataTables::of($clients)
            ->addColumn('client_name', function ($client) {
                return $client->name;
            })
            ->addColumn('client_id_number', function ($client) {
                return '#' . $client->id;
            })
            ->addColumn('mobile', function ($client) {
                return $client->mobile ?? 'N/A';
            })
            ->addColumn('branch_name', function ($client) {
                return $client->branch ? $client->branch->name : 'N/A';
            })
            ->addColumn('field_agent', function ($client) {
                if ($client->field_agent) {
                    return $client->field_agent->full_name . ' (' . $client->field_agent->agent_code . ')';
                }
                return '<span class="badge badge-warning">Unassigned</span>';
            })
            ->addColumn('action', function ($client) {
                $actions = '<button class="btn btn-sm btn-primary assign-agent-btn" data-client-id="' . $client->id . '" data-client-name="' . $client->name . '" data-current-agent="' . ($client->field_agent_id ?? '') . '"><i class="fa fa-user-plus"></i> Assign</button>';
                
                if ($client->field_agent_id) {
                    $actions .= ' <button class="btn btn-sm btn-danger unassign-agent-btn" data-client-id="' . $client->id . '"><i class="fa fa-user-times"></i> Remove</button>';
                }
                
                return $actions;
            })
            ->rawColumns(['field_agent', 'action'])
            ->make(true);
    }

    /**
     * Assign or update field agent for a client
     */
    public function updateAssignment(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'field_agent_id' => 'nullable|exists:field_agents,id',
        ]);

        try {
            $client = Client::findOrFail($request->client_id);
            $client->field_agent_id = $request->field_agent_id;
            $client->save();

            if ($request->field_agent_id) {
                $fieldAgent = FieldAgent::find($request->field_agent_id);
                return response()->json([
                    'success' => true,
                    'message' => 'Client assigned to ' . $fieldAgent->full_name . ' successfully.'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Field agent removed from client successfully.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment: ' . $e->getMessage()
            ], 500);
        }
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
        ]);

        try {
            Client::whereIn('id', $request->client_ids)
                  ->update(['field_agent_id' => $request->field_agent_id]);

            $fieldAgent = FieldAgent::find($request->field_agent_id);
            $count = count($request->client_ids);

            return response()->json([
                'success' => true,
                'message' => "{$count} client(s) assigned to {$fieldAgent->full_name} successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error in bulk assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get clients assigned to a specific field agent
     */
    public function getAgentClients($agentId)
    {
        $fieldAgent = FieldAgent::findOrFail($agentId);
        $clients = $fieldAgent->activeClients;

        return response()->json([
            'success' => true,
            'clients' => $clients
        ]);
    }
}
