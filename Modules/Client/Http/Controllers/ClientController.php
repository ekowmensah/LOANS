<?php

namespace Modules\Client\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Modules\Branch\Entities\Branch;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\ClientType;
use Modules\Client\Entities\ClientUser;
use Modules\Client\Entities\Profession;
use Modules\Client\Entities\Title;
use Modules\Client\Events\ClientCreated;
use Modules\Core\Entities\Country;
use Modules\CustomField\Entities\CustomField;
use Modules\Savings\Entities\Savings;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:client.clients.index'])->only(['index', 'show', 'get_clients']);
        $this->middleware(['permission:client.clients.create'])->only(['create', 'store', 'bulk_upload', 'validate_bulk_upload', 'bulk_upload_preview', 'process_bulk_upload', 'download_template', 'generate_savings_account']);
        $this->middleware(['permission:client.clients.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:client.clients.destroy'])->only(['destroy']);
        $this->middleware(['permission:client.clients.user.create'])->only(['store_user', 'create_user']);
        $this->middleware(['permission:client.clients.user.destroy'])->only(['destroy_user']);
        $this->middleware(['permission:client.clients.activate'])->only(['approve_client', 'reject_client', 'undo_approval', 'undo_rejection']);

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
        $status = $request->status;
        $data = Client::leftJoin("branches", "branches.id", "clients.branch_id")
            ->leftJoin("users", "users.id", "clients.loan_officer_id")
            ->leftJoin("savings", "savings.client_id", "=", "clients.id")
            ->leftJoin("group_members", "group_members.client_id", "=", "clients.id")
            ->leftJoin("loans", "loans.client_id", "=", "clients.id")
            ->when($orderBy, function (Builder $query) use ($orderBy, $orderByDir) {
                $query->orderBy($orderBy, $orderByDir);
            })
            ->when($search, function (Builder $query) use ($search) {
                $query->where('clients.first_name', 'like', "%$search%");
                $query->orWhere('clients.last_name', 'like', "%$search%");
                $query->orWhere('clients.account_number', 'like', "%$search%");
                $query->orWhere('clients.mobile', 'like', "%$search%");
                $query->orWhere('clients.external_id', 'like', "%$search%");
                $query->orWhere('clients.email', 'like', "%$search%");
            })
            ->when($status, function ($query) use ($status) {
                $query->where('clients.status', $status);
            })
            ->selectRaw("branches.name branch,concat(users.first_name,' ',users.last_name) staff,clients.id,clients.loan_officer_id,clients.first_name,clients.last_name,clients.gender,clients.mobile,clients.email,clients.external_id,clients.status,savings.account_number as savings_account,savings.balance_derived as savings_balance,group_members.group_id,COUNT(DISTINCT loans.id) as loan_count")
            ->groupBy('clients.id', 'branches.name', 'users.first_name', 'users.last_name', 'clients.loan_officer_id', 'clients.first_name', 'clients.last_name', 'clients.gender', 'clients.mobile', 'clients.email', 'clients.external_id', 'clients.status', 'savings.account_number', 'savings.balance_derived', 'group_members.group_id')
            ->paginate($perPage)
            ->appends($request->input());
        return theme_view('client::client.index', compact('data'));
    }

    public function get_clients(Request $request)
    {

        $status = $request->status;
        $query = DB::table("clients")
            ->leftJoin("branches", "branches.id", "clients.branch_id")
            ->leftJoin("users", "users.id", "clients.loan_officer_id")
            ->selectRaw("branches.name branch,concat(users.first_name,' ',users.last_name) staff,clients.id,clients.loan_officer_id,concat(clients.first_name,' ',clients.last_name) name,clients.gender,clients.mobile,clients.email,clients.external_id,clients.status")
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            });
        return DataTables::of($query)->editColumn('staff', function ($data) {
            return $data->staff;
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-navicon"></i></button> <ul class="dropdown-menu dropdown-menu-right" role="menu">';
            $action .= '<li><a href="' . url('client/' . $data->id . '/show') . '" class="">' . trans_choice('user::general.detail', 2) . '</a></li>';
            if (Auth::user()->hasPermissionTo('client.clients.edit')) {
                $action .= '<li><a href="' . url('client/' . $data->id . '/edit') . '" class="">' . trans_choice('user::general.edit', 2) . '</a></li>';
            }
            if (Auth::user()->hasPermissionTo('client.clients.destroy')) {
                $action .= '<li><a href="' . url('client/' . $data->id . '/destroy') . '" class="confirm">' . trans_choice('user::general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></li></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('client/' . $data->id . '/show') . '">' . $data->id . '</a>';

        })->editColumn('name', function ($data) {
            return '<a href="' . url('client/' . $data->id . '/show') . '">' . $data->name . '</a>';

        })->editColumn('gender', function ($data) {
            if ($data->gender == "male") {
                return trans_choice('core::general.male', 1);
            }
            if ($data->gender == "female") {
                return trans_choice('core::general.female', 1);
            }
            if ($data->gender == "other") {
                return trans_choice('core::general.other', 1);
            }
            if ($data->gender == "unspecified") {
                return trans_choice('core::general.unspecified', 1);
            }
        })->editColumn('status', function ($data) {
            if ($data->status == "pending") {
                return trans_choice('core::general.pending', 1);
            }
            if ($data->status == "active") {
                return trans_choice('core::general.active', 1);
            }
            if ($data->status == "inactive") {
                return trans_choice('core::general.inactive', 1);
            }
            if ($data->gender == "deceased") {
                return trans_choice('client::general.deceased', 1);
            }
            if ($data->gender == "unspecified") {
                return trans_choice('core::general.unspecified', 1);
            }
        })->rawColumns(['id', 'name', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $titles = Title::all();
        $professions = Profession::all();
        $client_types = ClientType::all();
        $users = User::whereHas('roles', function ($query) {
            return $query->where('name', '!=', 'client');
        })->get();
        $branches = Branch::all();
        $countries = Country::all();
        $custom_fields = CustomField::where('category', 'add_client')->where('active', 1)->get();
        return theme_view('client::client.create', compact('titles', 'professions', 'client_types', 'users', 'branches', 'countries', 'custom_fields'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'gender' => ['required'],
            'branch_id' => ['required'],
            'mobile' => ['required'],
            'country_id' => ['required'],
            'email' => ['nullable', 'email', 'max:255'],
            'dob' => ['required', 'date'],
            'created_date' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);
        // Set default client_type_id to Individual if not provided
        $client_type_id = $request->client_type_id;
        if (!$client_type_id) {
            $individualType = ClientType::where('name', 'Individual')->first();
            $client_type_id = $individualType ? $individualType->id : null;
        }
        
        // Set default country_id to Ghana if not provided
        $country_id = $request->country_id;
        if (!$country_id) {
            $ghana = Country::where('name', 'Ghana')->first();
            $country_id = $ghana ? $ghana->id : $request->country_id;
        }
        
        $client = new Client();
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->external_id = $request->external_id;
        $client->ghana_card = $request->ghana_card;
        $client->created_by_id = Auth::id();
        $client->gender = $request->gender;
        $client->country_id = $country_id;
        $client->loan_officer_id = $request->loan_officer_id;
        $client->title_id = $request->title_id;
        $client->branch_id = $request->branch_id;
        $client->client_type_id = $client_type_id;
        $client->profession_id = $request->profession_id;
        $client->mobile = $request->mobile;
        $client->notes = $request->notes;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->marital_status = $request->marital_status;
        $client->created_date = $request->created_date;
        $request->dob ? $client->dob = $request->dob : '';
        if ($request->hasFile('photo')) {
            $file_name = $request->file('photo')->store('public/uploads/clients');
            $client->photo = basename($file_name);
        }
        $client->save();
        custom_fields_save_form('add_client', $request, $client->id);
        activity()->on($client)
            ->withProperties(['id' => $client->id])
            ->log('Create Client');
        
        // Fire event to auto-create savings account
        event(new ClientCreated($client));
        
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('client');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $client = Client::with(['loan_officer', 'active_groups', 'group_memberships'])->find($id);
        $custom_fields = CustomField::where('category', 'add_client')->where('active', 1)->get();
        return theme_view('client::client.show', compact('client', 'custom_fields'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        $titles = Title::all();
        $professions = Profession::all();
        $client_types = ClientType::all();
        $users = User::whereHas('roles', function ($query) {
            return $query->where('name', '!=', 'client');
        })->get();
        $branches = Branch::all();
        $countries = Country::all();
        $custom_fields = CustomField::where('category', 'add_client')->where('active', 1)->get();
        return theme_view('client::client.edit', compact('client', 'titles', 'professions', 'client_types', 'users', 'branches', 'countries', 'custom_fields'));
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
            'first_name' => ['required'],
            'last_name' => ['required'],
            'gender' => ['required'],
            'email' => ['nullable', 'email', 'max:255'],
            'dob' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);
        $client = Client::find($id);
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->external_id = $request->external_id;
        $client->ghana_card = $request->ghana_card;
        $client->gender = $request->gender;
        $client->country_id = $request->country_id;
        $client->loan_officer_id = $request->loan_officer_id;
        $client->title_id = $request->title_id;
        $client->client_type_id = $request->client_type_id;
        $client->profession_id = $request->profession_id;
        $client->mobile = $request->mobile;
        $client->notes = $request->notes;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->marital_status = $request->marital_status;
        $request->dob ? $client->dob = $request->dob : '';
        if ($request->hasFile('photo')) {
            $file_name = $request->file('photo')->store('public/uploads/clients');
            //check if we had a file before
            if ($client->photo) {
                Storage::delete('public/uploads/clients/' . $client->photo);
            }
            $client->photo = basename($file_name);
        }
        $client->save();
        custom_fields_save_form('add_client', $request, $client->id);
        activity()->on($client)
            ->withProperties(['id' => $client->id])
            ->log('Update Client');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('client');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        
        // Check if client is part of a group
        $groupMembership = \DB::table('group_members')->where('client_id', $id)->first();
        if ($groupMembership) {
            \flash('Cannot delete client. Client is part of a group. Please remove from group first.')->error()->important();
            return redirect()->back();
        }
        
        // Check if client has any loans
        $hasLoans = \Modules\Loan\Entities\Loan::where('client_id', $id)->exists();
        if ($hasLoans) {
            \flash('Cannot delete client. Client has loan records.')->error()->important();
            return redirect()->back();
        }
        
        $client->delete();
        activity()->on($client)
            ->withProperties(['id' => $client->id])
            ->log('Delete Client');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }

    public function create_user($id)
    {
        $users = User::role('client')->get();
        $client = Client::find($id);
        return theme_view('client::client.create_user', compact('users', 'client'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store_user(Request $request, $id)
    {
        if ($request->existing == 1) {
            $request->validate([
                'user_id' => ['required'],
            ]);
            if (ClientUser::where('client_id', $id)->where('user_id', $request->user_id)->get()->count() > 0) {
                \flash(trans_choice("client::general.user_already_added", 1))->error()->important();
                return redirect()->back();
            }
            $client_user = new ClientUser();
            $client_user->client_id = $id;
            $client_user->created_by_id = Auth::id();
            $client_user->user_id = $request->user_id;
            $client_user->save();
        } else {
            $request->validate([
                'first_name' => ['required'],
                'last_name' => ['required'],
                'gender' => ['required'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'email_verified_at' => date("Y-m-d H:i:s")
            ]);
            //attach client role
            $role = Role::findByName('client');
            $user->assignRole($role);
            $client_user = new ClientUser();
            $client_user->client_id = $id;
            $client_user->created_by_id = Auth::id();
            $client_user->user_id = $user->id;
            $client_user->save();
        }
        activity()->log('Create Client User');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('client/' . $id . '/show');
    }

    public function destroy_user($id)
    {
        ClientUser::destroy($id);
        activity()->log('Delete Client User');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }

    public function approve_client(Request $request, $id)
    {
        $request->validate([
            'approved_on_date' => ['required', 'date'],
            'approved_notes' => ['nullable', 'string'],
        ]);
        
        $client = Client::find($id);
        
        if (!$client) {
            \flash('Client not found')->error()->important();
            return redirect()->back();
        }
        
        if ($client->status !== 'pending') {
            \flash('Only pending clients can be approved')->warning()->important();
            return redirect()->back();
        }
        
        $oldStatus = $client->status;
        
        $client->status = 'active';
        $client->approved_on_date = $request->approved_on_date;
        $client->approved_by_user_id = Auth::id();
        $client->approved_notes = $request->approved_notes;
        $client->save();
        
        // Fire event for status change
        event(new \Modules\Client\Events\ClientStatusChanged($client, $oldStatus, 'active'));
        
        activity()->on($client)
            ->withProperties(['id' => $client->id, 'approved_on' => $request->approved_on_date])
            ->log('Approve Client');
            
        \flash('Client approved successfully')->success()->important();
        return redirect()->back();
    }
    
    public function reject_client(Request $request, $id)
    {
        $request->validate([
            'rejected_notes' => ['required', 'string'],
        ]);
        
        $client = Client::find($id);
        
        if (!$client) {
            \flash('Client not found')->error()->important();
            return redirect()->back();
        }
        
        if ($client->status !== 'pending') {
            \flash('Only pending clients can be rejected')->warning()->important();
            return redirect()->back();
        }
        
        $oldStatus = $client->status;
        
        $client->status = 'rejected';
        $client->rejected_on_date = date('Y-m-d');
        $client->rejected_by_user_id = Auth::id();
        $client->rejected_notes = $request->rejected_notes;
        $client->save();
        
        // Fire event for status change
        event(new \Modules\Client\Events\ClientStatusChanged($client, $oldStatus, 'rejected'));
        
        activity()->on($client)
            ->withProperties(['id' => $client->id])
            ->log('Reject Client');
            
        \flash('Client rejected')->warning()->important();
        return redirect()->back();
    }
    
    public function undo_approval(Request $request, $id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            \flash('Client not found')->error()->important();
            return redirect()->back();
        }
        
        if ($client->status !== 'active') {
            \flash('Only active clients can have approval undone')->warning()->important();
            return redirect()->back();
        }
        
        $oldStatus = $client->status;
        
        $client->status = 'pending';
        $client->approved_on_date = null;
        $client->approved_by_user_id = null;
        $client->approved_notes = null;
        $client->save();
        
        // Fire event for status change
        event(new \Modules\Client\Events\ClientStatusChanged($client, $oldStatus, 'pending'));
        
        activity()->on($client)
            ->withProperties(['id' => $client->id])
            ->log('Undo Client Approval');
            
        \flash('Client approval undone successfully')->success()->important();
        return redirect()->back();
    }
    
    public function undo_rejection(Request $request, $id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            \flash('Client not found')->error()->important();
            return redirect()->back();
        }
        
        if ($client->status !== 'rejected') {
            \flash('Only rejected clients can have rejection undone')->warning()->important();
            return redirect()->back();
        }
        
        $oldStatus = $client->status;
        
        $client->status = 'pending';
        $client->rejected_on_date = null;
        $client->rejected_by_user_id = null;
        $client->rejected_notes = null;
        $client->save();
        
        // Fire event for status change
        event(new \Modules\Client\Events\ClientStatusChanged($client, $oldStatus, 'pending'));
        
        activity()->on($client)
            ->withProperties(['id' => $client->id])
            ->log('Undo Client Rejection');
            
        \flash('Client rejection undone successfully')->success()->important();
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $s = $request->s ?? $request->q; // Support both 's' and 'q' parameters
        
        // Return empty array if no search term
        if (!$s) {
            return response()->json([]);
        }
        
        // First, try to find client by their identifiers
        $client = Client::where('status', 'active')
            ->where(function($q) use ($s) {
                $q->where('mobile', '=', $s)
                    ->orWhere('ghana_card', '=', $s)
                    ->orWhere('external_id', '=', $s);
            })
            ->first();
        
        // If not found by client fields, try to find by savings account number
        if (!$client) {
            $savings = DB::table('savings')
                ->where('account_number', '=', $s)
                ->where('status', '=', 'active')
                ->first();
            
            if ($savings) {
                $client = Client::where('status', 'active')
                    ->where('id', $savings->client_id)
                    ->first();
            }
        }
        
        // If no client found, return empty array
        if (!$client) {
            return response()->json([]);
        }
        
        // Get client's savings account if exists
        $savingsAccount = DB::table('savings')
            ->where('client_id', $client->id)
            ->where('status', 'active')
            ->value('account_number');
        
        // Return client data
        return response()->json([[
            'id' => $client->id,
            'text' => $client->first_name . ' ' . $client->last_name . ' (' . $client->mobile . ')',
            'name' => $client->first_name . ' ' . $client->last_name,
            'name_id' => $client->first_name . ' ' . $client->last_name . ' (#' . $client->id . ')',
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'account_number' => $client->account_number,
            'external_id' => $client->external_id,
            'mobile' => $client->mobile,
            'ghana_card' => $client->ghana_card,
            'branch_id' => $client->branch_id,
            'loan_officer_id' => $client->loan_officer_id,
            'savings_account' => $savingsAccount,
            'branch' => $client->branch_id ? 'Branch ' . $client->branch_id : '',
            'existing_savings_count' => $savingsAccount ? 1 : 0
        ]]);
    }

    public function searchBySavings(Request $request)
    {
        $account = $request->account;
        
        if (!$account || strlen($account) < 2) {
            return response()->json([]);
        }
        
        return \DB::table('clients')
            ->join('savings', 'clients.id', '=', 'savings.client_id')
            ->where('clients.status', 'active')
            ->where('savings.status', 'active')
            ->where('savings.account_number', 'like', "%$account%")
            ->select('clients.id', 'clients.first_name', 'clients.last_name', 'savings.account_number as savings_account')
            ->limit(50)
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'savings_account' => $client->savings_account
                ];
            });
    }

    /**
     * Show bulk upload form
     */
    public function bulk_upload()
    {
        return theme_view('client::client.bulk_upload');
    }

    /**
     * Validate and preview bulk upload
     */
    public function validate_bulk_upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // 5MB max
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Get header row
            $header = array_shift($data);
            
            // Validate header
            $requiredColumns = ['first_name', 'last_name', 'gender', 'dob', 'branch_id', 'mobile'];
            $missingColumns = array_diff($requiredColumns, $header);
            
            if (!empty($missingColumns)) {
                Flash::error('Missing required columns: ' . implode(', ', $missingColumns));
                return redirect()->back();
            }
            
            // Validate each row and collect results
            $validRows = [];
            $invalidRows = [];
            $rowNumber = 2; // Start from 2 (1 is header)
            
            // Track duplicates within CSV
            $csvExternalIds = [];
            $csvMobiles = [];
            $csvGhanaCards = [];
            
            foreach ($data as $index => $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map row to associative array
                $clientData = array_combine($header, $row);
                $errors = [];
                
                // Validate required fields
                if (empty($clientData['first_name'])) {
                    $errors[] = 'First name is required';
                }
                if (empty($clientData['last_name'])) {
                    $errors[] = 'Last name is required';
                }
                if (empty($clientData['gender'])) {
                    $errors[] = 'Gender is required';
                } elseif (!in_array(strtolower($clientData['gender']), ['male', 'female'])) {
                    $errors[] = 'Gender must be male or female';
                }
                if (empty($clientData['dob'])) {
                    $errors[] = 'Date of birth is required';
                } elseif (!strtotime($clientData['dob'])) {
                    $errors[] = 'Invalid date format for DOB';
                }
                if (empty($clientData['branch_id'])) {
                    $errors[] = 'Branch ID is required';
                } elseif (!Branch::find($clientData['branch_id'])) {
                    $errors[] = 'Invalid branch ID';
                }
                if (empty($clientData['mobile'])) {
                    $errors[] = 'Mobile is required';
                }
                
                // Validate email if provided
                if (!empty($clientData['email']) && !filter_var($clientData['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Invalid email format';
                }
                
                // Check for duplicates in database
                if (!empty($clientData['external_id'])) {
                    $existingClient = Client::where('external_id', $clientData['external_id'])->first();
                    if ($existingClient) {
                        $errors[] = 'External ID already exists in database (Client: ' . $existingClient->name . ')';
                    }
                    
                    // Check for duplicates within CSV
                    if (isset($csvExternalIds[$clientData['external_id']])) {
                        $errors[] = 'External ID duplicated in CSV (also in row ' . $csvExternalIds[$clientData['external_id']] . ')';
                    } else {
                        $csvExternalIds[$clientData['external_id']] = $rowNumber;
                    }
                }
                
                if (!empty($clientData['mobile'])) {
                    $existingClient = Client::where('mobile', $clientData['mobile'])->first();
                    if ($existingClient) {
                        $errors[] = 'Mobile number already exists in database (Client: ' . $existingClient->name . ')';
                    }
                    
                    // Check for duplicates within CSV
                    if (isset($csvMobiles[$clientData['mobile']])) {
                        $errors[] = 'Mobile number duplicated in CSV (also in row ' . $csvMobiles[$clientData['mobile']] . ')';
                    } else {
                        $csvMobiles[$clientData['mobile']] = $rowNumber;
                    }
                }
                
                if (!empty($clientData['ghana_card'])) {
                    $existingClient = Client::where('ghana_card', $clientData['ghana_card'])->first();
                    if ($existingClient) {
                        $errors[] = 'Ghana Card already exists in database (Client: ' . $existingClient->name . ')';
                    }
                    
                    // Check for duplicates within CSV
                    if (isset($csvGhanaCards[$clientData['ghana_card']])) {
                        $errors[] = 'Ghana Card duplicated in CSV (also in row ' . $csvGhanaCards[$clientData['ghana_card']] . ')';
                    } else {
                        $csvGhanaCards[$clientData['ghana_card']] = $rowNumber;
                    }
                }
                
                $rowData = [
                    'row_number' => $rowNumber,
                    'data' => $clientData,
                    'errors' => $errors
                ];
                
                if (empty($errors)) {
                    $validRows[] = $rowData;
                } else {
                    $invalidRows[] = $rowData;
                }
                
                $rowNumber++;
            }
            
            // Store data in session for later processing
            $uploadData = [
                'header' => $header,
                'valid_rows' => $validRows,
                'invalid_rows' => $invalidRows,
                'total_rows' => count($data),
                'valid_count' => count($validRows),
                'invalid_count' => count($invalidRows)
            ];
            
            session(['bulk_upload_data' => $uploadData]);
            
            return redirect('client/bulk-upload-preview');
            
        } catch (\Exception $e) {
            Flash::error('Error processing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    
    /**
     * Show preview of bulk upload validation
     */
    public function bulk_upload_preview()
    {
        $uploadData = session('bulk_upload_data');
        
        if (!$uploadData) {
            Flash::warning('No upload data found. Please upload a file first.');
            return redirect('client/bulk-upload');
        }
        
        return theme_view('client::client.bulk_upload_preview', compact('uploadData'));
    }

    /**
     * Process bulk upload after validation
     */
    public function process_bulk_upload(Request $request)
    {
        $uploadData = session('bulk_upload_data');
        
        if (!$uploadData) {
            Flash::error('No upload data found. Please upload a file first.');
            return redirect('client/bulk-upload');
        }
        
        try {
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            // Process only valid rows
            foreach ($uploadData['valid_rows'] as $rowData) {
                try {
                    $clientData = $rowData['data'];
                    
                    // Set default client_type_id to Individual if not provided
                    $client_type_id = $clientData['client_type_id'] ?? null;
                    if (!$client_type_id) {
                        $individualType = ClientType::where('name', 'Individual')->first();
                        $client_type_id = $individualType ? $individualType->id : null;
                    }
                    
                    // Set default country_id to Ghana if not provided
                    $country_id = $clientData['country_id'] ?? null;
                    if (!$country_id) {
                        $ghana = Country::where('name', 'Ghana')->first();
                        $country_id = $ghana ? $ghana->id : null;
                    }
                    
                    // Create client
                    $client = new Client();
                    $client->first_name = $clientData['first_name'];
                    $client->last_name = $clientData['last_name'];
                    $client->middle_name = $clientData['middle_name'] ?? null;
                    $client->external_id = $clientData['external_id'] ?? null;
                    $client->ghana_card = $clientData['ghana_card'] ?? null;
                    $client->created_by_id = Auth::id();
                    $client->gender = strtolower($clientData['gender']);
                    $client->branch_id = $clientData['branch_id'];
                    $client->dob = $clientData['dob'];
                    $client->mobile = $clientData['mobile'];
                    $client->email = $clientData['email'] ?? null;
                    $client->address = $clientData['address'] ?? null;
                    $client->marital_status = $clientData['marital_status'] ?? null;
                    $client->loan_officer_id = $clientData['loan_officer_id'] ?? null;
                    $client->title_id = $clientData['title_id'] ?? null;
                    $client->profession_id = $clientData['profession_id'] ?? null;
                    $client->client_type_id = $client_type_id;
                    $client->country_id = $country_id;
                    $client->notes = $clientData['notes'] ?? null;
                    $client->created_date = $clientData['created_date'] ?? date('Y-m-d');
                    
                    $client->save();
                    
                    activity()->on($client)
                        ->withProperties(['id' => $client->id])
                        ->log('Bulk Upload Client');
                    
                    // Fire event to auto-create savings account
                    event(new ClientCreated($client));
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Row " . $rowData['row_number'] . ": " . $e->getMessage();
                    $errorCount++;
                }
            }
            
            DB::commit();
            
            // Clear session data
            session()->forget('bulk_upload_data');
            
            $message = "Successfully imported $successCount client(s).";
            if ($errorCount > 0) {
                $message .= " $errorCount row(s) failed.";
            }
            
            Flash::success($message);
            
            if (!empty($errors)) {
                session()->flash('upload_errors', $errors);
            }
            
            return redirect('client');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Flash::error('Error processing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Download sample CSV template
     */
    public function download_template()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="client_upload_template.csv"',
        ];

        $columns = [
            'first_name',
            'last_name',
            'middle_name',
            'gender',
            'dob',
            'branch_id',
            'external_id',
            'ghana_card',
            'mobile',
            'email',
            'address',
            'marital_status',
            'loan_officer_id',
            'title_id',
            'profession_id',
            'client_type_id',
            'country_id',
            'notes',
            'created_date'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample row with notes
            fputcsv($file, [
                'John',
                'Doe',
                'K',
                'male',
                '1990-01-15',
                '1',
                'EXT001',
                'GHA-123456789-1',
                '0244123456',
                'john.doe@example.com',
                '123 Main Street, Accra',
                'single',
                '1',
                '1',
                '1',
                '',  // client_type_id - leave empty, defaults to Individual
                '',  // country_id - leave empty, defaults to Ghana
                'Sample client notes',
                date('Y-m-d')
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate default savings account for a client
     */
    public function generate_savings_account($id)
    {
        try {
            $client = Client::find($id);
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }
            
            // Check if client already has a savings account
            $existingSavings = Savings::where('client_id', $client->id)->first();
            if ($existingSavings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client already has a savings account: ' . $existingSavings->account_number
                ], 400);
            }
            
            // Fire the event to create savings account
            event(new ClientCreated($client));
            
            // Check if account was created
            $newSavings = Savings::where('client_id', $client->id)->first();
            
            if ($newSavings) {
                activity()->on($client)
                    ->withProperties(['savings_id' => $newSavings->id, 'account_number' => $newSavings->account_number])
                    ->log('Manually Generated Savings Account');
                    
                return response()->json([
                    'success' => true,
                    'message' => 'Savings account generated successfully: ' . $newSavings->account_number,
                    'account_number' => $newSavings->account_number
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate savings account. Please check settings and logs.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Error generating savings account for client ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

}
