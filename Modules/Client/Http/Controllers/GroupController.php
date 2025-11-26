<?php

namespace Modules\Client\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Modules\Client\Entities\Group;
use Modules\Client\Entities\GroupMember;
use Modules\Client\Entities\Client;
use Modules\Branch\Entities\Branch;
use Modules\User\Entities\User;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:client.groups.index'])->only(['index', 'show']);
        $this->middleware(['permission:client.groups.create'])->only(['create', 'store']);
        $this->middleware(['permission:client.groups.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:client.groups.destroy'])->only(['destroy']);
        $this->middleware(['permission:client.groups.manage_members'])->only(['addMember', 'removeMember', 'updateMember']);
    }

    /**
     * Display a listing of groups
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?: 20;
        $orderBy = $request->order_by;
        $orderByDir = $request->order_by_dir;
        $search = $request->s;
        $status = $request->status;
        $branch_id = $request->branch_id;
        $loan_officer_id = $request->loan_officer_id;

        $data = Group::with(['branch', 'loan_officer'])
            ->when($orderBy, function (Builder $query) use ($orderBy, $orderByDir) {
                $query->orderBy($orderBy, $orderByDir);
            })
            ->when($search, function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
            })
            ->when($status, function (Builder $query) use ($status) {
                $query->where('status', $status);
            })
            ->when($branch_id, function (Builder $query) use ($branch_id) {
                $query->where('branch_id', $branch_id);
            })
            ->when($loan_officer_id, function (Builder $query) use ($loan_officer_id) {
                $query->where('loan_officer_id', $loan_officer_id);
            })
            ->paginate($perPage)
            ->appends($request->input());

        $branches = Branch::all();
        $loan_officers = User::all();

        return theme_view('client::group.index', compact('data', 'branches', 'loan_officers'));
    }

    /**
     * DataTables endpoint for groups
     */
    public function get_groups(Request $request)
    {
        $query = Group::with(['branch', 'loan_officer'])
            ->withCount(['active_members', 'loans']);

        return DataTables::of($query)
            ->editColumn('action', function ($data) {
                $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-navicon"></i></button> <ul class="dropdown-menu dropdown-menu-right" role="menu">';
                
                $action .= '<li><a href="' . url('client/group/' . $data->id . '/show') . '" class="">' . trans_choice('core::general.view', 1) . '</a></li>';
                
                if (Auth::user()->hasPermissionTo('client.groups.edit')) {
                    $action .= '<li><a href="' . url('client/group/' . $data->id . '/edit') . '" class="">' . trans_choice('core::general.edit', 1) . '</a></li>';
                }
                
                if (Auth::user()->hasPermissionTo('client.groups.manage_members')) {
                    $action .= '<li><a href="' . url('client/group/' . $data->id . '/members') . '" class="">Manage Members</a></li>';
                }
                
                if (Auth::user()->hasPermissionTo('client.groups.destroy')) {
                    $action .= '<li><a href="' . url('client/group/' . $data->id . '/destroy') . '" class="confirm">' . trans_choice('core::general.delete', 1) . '</a></li>';
                }
                
                $action .= "</ul></li></div>";
                return $action;
            })
            ->editColumn('name', function ($data) {
                return '<a href="' . url('client/group/' . $data->id . '/show') . '">' . $data->name . '</a>';
            })
            ->editColumn('branch', function ($data) {
                return $data->branch ? $data->branch->name : '-';
            })
            ->editColumn('loan_officer', function ($data) {
                return $data->loan_officer ? $data->loan_officer->first_name . ' ' . $data->loan_officer->last_name : '-';
            })
            ->editColumn('status', function ($data) {
                $class = $data->status === 'active' ? 'success' : 'warning';
                return '<span class="badge badge-' . $class . '">' . ucfirst($data->status) . '</span>';
            })
            ->addColumn('member_count', function ($data) {
                return $data->active_members_count;
            })
            ->addColumn('loan_count', function ($data) {
                return $data->loans_count;
            })
            ->rawColumns(['name', 'action', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new group
     */
    public function create()
    {
        $branches = Branch::all();
        $loan_officers = User::all();

        return theme_view('client::group.create', compact('branches', 'loan_officers'));
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

        $group = new Group();
        $group->fill($request->all());
        $group->save();

        activity()->on($group)
            ->withProperties(['id' => $group->id])
            ->log('Create Group');

        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('client/group');
    }

    /**
     * Display the specified group
     */
    public function show($id)
    {
        $group = Group::with(['branch', 'loan_officer', 'active_members.client', 'loans'])
            ->findOrFail($id);

        $clients = Client::where('status', 'active')->get();

        return theme_view('client::group.show', compact('group', 'clients'));
    }

    /**
     * Show the form for editing the specified group
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $branches = Branch::all();
        $loan_officers = User::all();

        return theme_view('client::group.edit', compact('group', 'branches', 'loan_officers'));
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
        $group->fill($request->all());
        $group->save();

        activity()->on($group)
            ->withProperties(['id' => $group->id])
            ->log('Update Group');

        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('client/group');
    }

    /**
     * Remove the specified group
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        
        // Check if group has active loans
        if ($group->loans()->where('status', 'active')->exists()) {
            \flash('Cannot delete group with active loans')->error()->important();
            return redirect()->back();
        }

        // Remove all group members first
        $group->members()->delete();
        
        $group->delete();

        activity()->on($group)
            ->withProperties(['id' => $group->id])
            ->log('Delete Group');

        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
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
        
        // Check if group has active loans
        if ($group->loans()->where('status', 'active')->exists()) {
            \flash('Cannot add members to group with active loans')->error()->important();
            return redirect()->back();
        }
        
        // Check if client is already a member of THIS group
        if ($group->members()->where('client_id', $request->client_id)->where('status', 'active')->exists()) {
            \flash('Client is already an active member of this group')->error()->important();
            return redirect()->back();
        }
        
        // Check if client is already a member of ANY other group
        $existingMembership = GroupMember::where('client_id', $request->client_id)
            ->where('status', 'active')
            ->where('group_id', '!=', $id)
            ->with('group')
            ->first();
            
        if ($existingMembership) {
            \flash('Client is already an active member of another group: ' . $existingMembership->group->name)->error()->important();
            return redirect()->back();
        }

        $member = new GroupMember();
        $member->group_id = $id;
        $member->client_id = $request->client_id;
        $member->role = $request->role;
        $member->status = 'active';
        $member->joined_at = now();
        $member->save();

        activity()->on($member)
            ->withProperties(['group_id' => $id, 'client_id' => $request->client_id])
            ->log('Add Group Member');

        \flash('Member added successfully')->success()->important();
        return redirect()->back();
    }

    /**
     * Remove a member from the group
     */
    public function removeMember($id, $memberId)
    {
        $group = Group::findOrFail($id);
        
        // Check if group has active loans
        if ($group->loans()->where('status', 'active')->exists()) {
            \flash('Cannot remove members from group with active loans')->error()->important();
            return redirect()->back();
        }

        $member = GroupMember::where('group_id', $id)
            ->where('id', $memberId)
            ->firstOrFail();

        // Log the removal before deleting
        activity()->on($member)
            ->withProperties(['group_id' => $id, 'member_id' => $memberId, 'client_id' => $member->client_id])
            ->log('Remove Group Member');

        // Actually delete the member record
        $member->delete();

        \flash('Member removed successfully')->success()->important();
        return redirect()->back();
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

        $member->role = $request->role;
        $member->save();

        activity()->on($member)
            ->withProperties(['group_id' => $id, 'member_id' => $memberId, 'new_role' => $request->role])
            ->log('Update Group Member Role');

        \flash('Member role updated successfully')->success()->important();
        return redirect()->back();
    }
}
