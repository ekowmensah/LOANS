<?php

namespace Modules\Client\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Client\Entities\Group;
use Modules\Client\Entities\GroupMember;
use Modules\Client\Entities\Client;
use Modules\Branch\Entities\Branch;
use Modules\User\Entities\User;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'client']);
    }

    /**
     * Display a listing of groups
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?: 20;
        $search = $request->search;
        $status = $request->status;
        $branch_id = $request->branch_id;

        $query = Group::with(['branch', 'loan_officer'])
            ->withCount(['active_members', 'loans']);

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        }

        $groups = $query->paginate($perPage);

        return response()->json([
            'data' => $groups->items(),
            'meta' => [
                'current_page' => $groups->currentPage(),
                'last_page' => $groups->lastPage(),
                'per_page' => $groups->perPage(),
                'total' => $groups->total()
            ]
        ]);
    }

    /**
     * Store a newly created group
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'loan_officer_id' => ['required', 'exists:users,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'meeting_frequency' => ['required', 'in:weekly,biweekly,monthly'],
            'meeting_day' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive,closed']
        ]);

        $group = Group::create($request->all());

        return response()->json([
            'message' => 'Group created successfully',
            'data' => $group->load(['branch', 'loan_officer'])
        ], 201);
    }

    /**
     * Display the specified group
     */
    public function show($id)
    {
        $group = Group::with(['branch', 'loan_officer', 'active_members.client', 'loans'])
            ->withCount(['active_members', 'loans'])
            ->findOrFail($id);

        return response()->json([
            'data' => $group
        ]);
    }

    /**
     * Update the specified group
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'loan_officer_id' => ['required', 'exists:users,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'meeting_frequency' => ['required', 'in:weekly,biweekly,monthly'],
            'meeting_day' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive,closed']
        ]);

        $group = Group::findOrFail($id);
        $group->update($request->all());

        return response()->json([
            'message' => 'Group updated successfully',
            'data' => $group->load(['branch', 'loan_officer'])
        ]);
    }

    /**
     * Remove the specified group
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        
        // Check if group has active loans
        if ($group->loans()->where('status', 'active')->exists()) {
            return response()->json([
                'message' => 'Cannot delete group with active loans'
            ], 422);
        }

        // Remove all group members first
        $group->members()->delete();
        $group->delete();

        return response()->json([
            'message' => 'Group deleted successfully'
        ]);
    }

    /**
     * Add a member to the group
     */
    public function addMember(Request $request, $id)
    {
        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'role' => ['required', 'in:member,leader,treasurer,secretary']
        ]);

        $group = Group::findOrFail($id);
        
        // Check if client is already a member
        if ($group->members()->where('client_id', $request->client_id)->where('status', 'active')->exists()) {
            return response()->json([
                'message' => 'Client is already an active member of this group'
            ], 422);
        }

        $member = GroupMember::create([
            'group_id' => $id,
            'client_id' => $request->client_id,
            'role' => $request->role,
            'status' => 'active',
            'joined_at' => now()
        ]);

        return response()->json([
            'message' => 'Member added successfully',
            'data' => $member->load('client')
        ], 201);
    }

    /**
     * Remove a member from the group
     */
    public function removeMember($id, $memberId)
    {
        $member = GroupMember::where('group_id', $id)
            ->where('id', $memberId)
            ->firstOrFail();

        $member->update([
            'status' => 'inactive',
            'left_at' => now()
        ]);

        return response()->json([
            'message' => 'Member removed successfully'
        ]);
    }

    /**
     * Update member role
     */
    public function updateMember(Request $request, $id, $memberId)
    {
        $request->validate([
            'role' => ['required', 'in:member,leader,treasurer,secretary']
        ]);

        $member = GroupMember::where('group_id', $id)
            ->where('id', $memberId)
            ->firstOrFail();

        $member->update(['role' => $request->role]);

        return response()->json([
            'message' => 'Member role updated successfully',
            'data' => $member->load('client')
        ]);
    }

    /**
     * Get group members
     */
    public function getMembers($id)
    {
        $group = Group::findOrFail($id);
        $members = $group->active_members()->with('client')->get();

        return response()->json([
            'data' => $members
        ]);
    }

    /**
     * Get group loans
     */
    public function getLoans($id)
    {
        $group = Group::findOrFail($id);
        $loans = $group->loans()->with(['loan_product', 'currency', 'loan_officer'])->get();

        return response()->json([
            'data' => $loans
        ]);
    }
}
