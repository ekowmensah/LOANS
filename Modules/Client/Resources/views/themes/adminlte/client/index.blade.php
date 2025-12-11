@extends('core::layouts.master')
@section('title')
    {{ trans_choice('client::general.client',2) }}
@endsection
@section('styles')
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('client::general.client',2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('client::general.client',2) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                @can('client.clients.create')
                    <a href="{{ url('client/create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus"></i> {{ trans_choice('core::general.add',1) }} {{ trans_choice('client::general.client',1) }}
                    </a>
                    <a href="{{ url('client/bulk-upload') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-upload"></i> Bulk Upload
                    </a>
                @endcan
                @if(request('status') == 'pending')
                    @can('client.clients.activate')
                        <button type="button" class="btn btn-primary btn-sm" id="bulkApproveBtn" style="display: none;" data-toggle="modal" data-target="#bulkApproveModal">
                            <i class="fas fa-check-circle"></i> Bulk Approve (<span id="selectedCount">0</span>)
                        </button>
                    @endcan
                @endif
                <div class="btn-group">
                    <div class="dropdown">
                        <a href="#" class="btn btn-trigger btn-icon dropdown-toggle"
                           data-toggle="dropdown">
                            <i class="fas fa-wrench"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xs">
                            <a class="dropdown-item"><span>Show</span></a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>10])}}"
                               class="dropdown-item {{request('per_page')==10?'active':''}}">
                                10
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>20])}}"
                               class="dropdown-item {{(request('per_page')==20||!request('per_page'))?'active':''}}">
                                20
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>50])}}"
                               class="dropdown-item {{request('per_page')==50?'active':''}}">50</a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>'all'])}}"
                               class="dropdown-item {{request('per_page')=='all'?'active':''}}">All</a>
                            <a class="dropdown-item">Order</a>
                            <a href="{{request()->fullUrlWithQuery(['order_by_dir'=>'asc'])}}"
                               class="dropdown-item {{(request('order_by_dir')=='asc'||!request('order_by_dir'))?'active':''}}">
                                ASC
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['order_by_dir'=>'desc'])}}"
                               class="dropdown-item {{request('order_by_dir')=='desc'?'active':''}}">
                                DESC
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-tools">
                    <form class="form-inline ml-0 ml-md-3" action="{{url('client')}}">
                        <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{request('status')=='pending'?'selected':''}}>Pending</option>
                            <option value="active" {{request('status')=='active'?'selected':''}}>Active</option>
                            <option value="rejected" {{request('status')=='rejected'?'selected':''}}>Rejected</option>
                            <option value="inactive" {{request('status')=='inactive'?'selected':''}}>Inactive</option>
                        </select>
                        <div class="input-group input-group-sm">
                            <input type="text" name="s" class="form-control" value="{{request('s')}}"
                                   placeholder="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table  table-striped table-hover table-condensed" id="data-table">
                    <thead>
                    <tr>
                        @if(request('status') == 'pending')
                            @can('client.clients.activate')
                                <th style="width: 40px;">
                                    <input type="checkbox" id="selectAll" title="Select All">
                                </th>
                            @endcan
                        @endif
                        <th style="width: 60px;">Photo</th>
                        <th>
                            <a href="{{table_order_link('name')}}">
                                {{ trans_choice('core::general.name',1) }}
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('external_id')}}">
                                {{ trans_choice('core::general.external_id',1) }}
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('gender')}}">
                                {{ trans('core::general.gender') }}
                            </a>
                        </th>
                        <th>{{ trans('core::general.mobile') }}</th>
                        <th>Savings Account</th>
                        <th>
                            <a href="{{table_order_link('status')}}">
                                {{ trans_choice('core::general.status',1) }}
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('branch')}}">
                                {{ trans_choice('core::general.branch',1) }}
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('staff')}}">
                                {{ trans_choice('core::general.staff',1) }}
                            </a>
                        </th>
                        <th>Field Officer</th>
                        <th>{{ trans_choice('core::general.action',1) }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key)
                        <tr>
                            @if(request('status') == 'pending')
                                @can('client.clients.activate')
                                    <td>
                                        <input type="checkbox" class="client-checkbox" value="{{$key->id}}" data-name="{{$key->name}}" data-mobile="{{$key->mobile}}" data-account="{{$key->savings_account ?? 'N/A'}}">
                                    </td>
                                @endcan
                            @endif
                            <td>
                                <div style="position: relative; width: 40px; height: 40px; display: inline-block;">
                                    @if($key->photo)
                                        <img src="{{asset('storage/'.$key->photo)}}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #667eea;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                            {{strtoupper(substr($key->name, 0, 1))}}
                                        </div>
                                        <a href="{{url('client/' . $key->id . '/edit')}}" title="Add Photo" style="position: absolute; bottom: -2px; right: -2px; width: 18px; height: 18px; border-radius: 50%; background: #667eea; display: flex; align-items: center; justify-content: center; border: 2px solid white; text-decoration: none;">
                                            <i class="fas fa-camera" style="font-size: 8px; color: white;"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{url('client/' . $key->id . '/show')}}">
                                    <span>{{$key->name}}</span>
                                </a>
                            </td>
                            <td>
                                <span>{{$key->external_id}}</span>
                            </td>
                            <td>
                                @if($key->gender == "male")
                                    <span>{{trans_choice('core::general.male',1)}}</span>
                                @endif
                                @if($key->gender == "female")
                                    <span>{{trans_choice('core::general.female',1)}}</span>
                                @endif
                                @if($key->gender == "other")
                                    <span>{{trans_choice('core::general.other',1)}}</span>
                                @endif
                                @if($key->gender == "unspecified")
                                    <span>{{trans_choice('core::general.unspecified',1)}}</span>
                                @endif
                            </td>
                            <td>
                                <span>{{$key->mobile}}</span>
                            </td>
                            <td>
                                @if($key->savings_account)
                                    <span class="badge badge-info">{{$key->savings_account}}</span>
                                    <br>
                                    <small class="text-muted">Balance: <strong>{{number_format($key->savings_balance ?? 0, 2)}}</strong></small>
                                @else
                                    <button type="button" class="btn btn-sm btn-success generate-savings-btn" 
                                            data-client-id="{{$key->id}}" 
                                            data-client-name="{{$key->name}}"
                                            title="Generate Default Savings Account">
                                        <i class="fas fa-plus-circle"></i> Generate Account
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($key->status == "pending")
                                    <span class="badge badge-warning">{{trans_choice('core::general.pending',1)}}</span>
                                @endif
                                @if($key->status == "active")
                                    <span class="badge badge-success">{{trans_choice('core::general.active',1)}}</span>
                                @endif
                                @if($key->status == "inactive")
                                    <span class="badge badge-secondary">{{trans_choice('core::general.inactive',1)}}</span>
                                @endif
                                @if($key->status == "rejected")
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                                @if($key->status == "deceased")
                                    <span class="badge badge-dark">{{trans_choice('client::general.deceased',1)}}</span>
                                @endif
                                @if($key->status == "unspecified")
                                    <span class="badge badge-light">{{trans_choice('core::general.unspecified',1)}}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{url('branch/' . $key->branch_id . '/show')}}">
                                    <span>{{$key->branch}}</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{url('user/' . $key->loan_officer_id . '/show')}}">
                                    <span>{{$key->staff}}</span>
                                </a>
                            </td>
                            <td>
                                @if($key->field_agent)
                                    <span class="badge badge-primary">
                                        <i class="fas fa-user-tie"></i> {{$key->field_agent->full_name}}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{$key->field_agent->agent_code}}</small>
                                @else
                                    <button type="button" class="btn btn-sm btn-warning assign-field-officer-btn" 
                                            data-client-id="{{$key->id}}" 
                                            data-client-name="{{$key->name}}"
                                            title="Assign Field Officer">
                                        <i class="fas fa-user-plus"></i> Assign
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($key->status == 'pending')
                                    @can('client.clients.activate')
                                        <button type="button" class="btn btn-sm btn-success mb-1 approve-btn" 
                                                data-client-id="{{$key->id}}" 
                                                data-client-name="{{$key->name}}"
                                                title="Approve Client">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger mb-1 reject-btn" 
                                                data-client-id="{{$key->id}}" 
                                                data-client-name="{{$key->name}}"
                                                title="Reject Client">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @endcan
                                @endif
                                
                                @if(!$key->group_id)
                                    <a href="{{url('client/group/member/create?client_id=' . $key->id)}}" class="btn btn-sm btn-info mb-1">
                                        <i class="fas fa-users"></i> Add to Group
                                    </a>
                                @endif
                                
                                <div class="btn-group">
                                    <button href="#" class="btn btn-default dropdown-toggle btn-sm"
                                            data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{url('client/' . $key->id . '/show')}}" class="dropdown-item">
                                            <i class="far fa-eye"></i>
                                            <span>{{trans_choice('core::general.detail',2)}}</span>
                                        </a>
                                        @can('core.payment_types.edit')
                                            <a href="{{url('client/' . $key->id . '/edit')}}" class="dropdown-item">
                                                <i class="far fa-edit"></i>
                                                <span>{{trans_choice('core::general.edit',1)}}</span>
                                            </a>
                                        @endcan
                                        
                                        @if($key->status == 'active')
                                            @can('client.clients.activate')
                                                <div class="divider"></div>
                                                <a href="{{url('client/' . $key->id . '/undo_approval')}}" class="dropdown-item confirm">
                                                    <i class="fas fa-undo"></i>
                                                    <span>Undo Approval</span>
                                                </a>
                                            @endcan
                                        @endif
                                        
                                        @if($key->status == 'rejected')
                                            @can('client.clients.activate')
                                                <div class="divider"></div>
                                                <a href="{{url('client/' . $key->id . '/undo_rejection')}}" class="dropdown-item confirm">
                                                    <i class="fas fa-undo"></i>
                                                    <span>Undo Rejection</span>
                                                </a>
                                            @endcan
                                        @endif
                                        
                                        @can('core.payment_types.destroy')
                                            @if(!$key->group_id && $key->loan_count == 0)
                                                <div class="divider"></div>
                                                <a href="{{url('client/' . $key->id . '/destroy')}}"
                                                   class="dropdown-item confirm">
                                                    <i class="fas fa-trash"></i>
                                                    <span>{{trans_choice('core::general.delete',1)}}</span>
                                                </a>
                                            @else
                                                <div class="divider"></div>
                                                <a href="#" class="dropdown-item disabled text-muted" title="Cannot delete: Client has {{ $key->group_id ? 'group membership' : '' }}{{ $key->group_id && $key->loan_count > 0 ? ' and ' : '' }}{{ $key->loan_count > 0 ? 'loans' : '' }}">
                                                    <i class="fas fa-ban"></i>
                                                    <span>{{trans_choice('core::general.delete',1)}} (Restricted)</span>
                                                </a>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
                        <div>{{ trans_choice('core::general.page',1) }} {{$data->currentPage()}} {{ trans_choice('core::general.of',1) }} {{$data->lastPage()}}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-center">
                            {{$data->links()}}
                        </div>
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>

            </div>
        </div>
    </section>
    
    <!-- Approve Client Modal -->
    <div class="modal fade" id="approve_client_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Client</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="approve_client_form" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to approve <strong id="approve_client_name"></strong>?</p>
                        <div class="form-group">
                            <label for="approved_on_date">Approval Date <span class="text-danger">*</span></label>
                            <input type="date" name="approved_on_date" id="approved_on_date" class="form-control" value="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="approved_notes">Notes (Optional)</label>
                            <textarea name="approved_notes" id="approved_notes" class="form-control" rows="3" placeholder="Enter any approval notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Approve Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Reject Client Modal -->
    <div class="modal fade" id="reject_client_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Client</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="reject_client_form" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to reject <strong id="reject_client_name"></strong>?</p>
                        <div class="form-group">
                            <label for="rejected_notes">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejected_notes" id="rejected_notes" class="form-control" rows="4" placeholder="Enter reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Reject Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Field Officer Modal -->
    <div class="modal fade" id="assignFieldOfficerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Field Officer</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Client: <strong id="field-officer-client-name"></strong></p>
                    <div class="form-group">
                        <label>Select Field Officer <span class="text-danger">*</span></label>
                        <select class="form-control" id="field-officer-id">
                            <option value="">-- Select Field Officer --</option>
                            @if(isset($fieldAgents))
                                @foreach($fieldAgents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->agent_code }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-field-officer-btn">
                        <i class="fas fa-save"></i> Assign Field Officer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Approve Modal -->
    <div class="modal fade" id="bulkApproveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{url('client/bulk-approve')}}" id="bulkApproveForm">
                    {{csrf_field()}}
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle text-success"></i> Bulk Approve Clients
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> You are about to approve <strong id="bulkSelectedCount">0</strong> client(s).
                        </div>
                        
                        <div class="form-group">
                            <label>Selected Clients:</label>
                            <ul id="selectedClientsList" style="max-height: 200px; overflow-y: auto; padding-left: 20px;">
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <label>Approval Date <span class="text-danger">*</span></label>
                            <input type="date" name="approved_on_date" class="form-control" value="{{date('Y-m-d')}}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Approval Notes</label>
                            <textarea name="approved_notes" class="form-control" rows="3" placeholder="Optional notes for this approval"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Approve All
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: "#app",
            data: {
                records:{!!json_encode($data)!!},
                selectAll: false,
                selectedRecords: []
            },
            methods: {
                selectAllRecords() {
                    this.selectedRecords = [];
                    if (this.selectAll) {
                        this.records.data.forEach(item => {
                            this.selectedRecords.push(item.id);
                        });
                    }
                },
            },
        })

        // Handle Approve Client button
        $(document).on('click', '.approve-btn', function() {
            var btn = $(this);
            var clientId = btn.data('client-id');
            var clientName = btn.data('client-name');
            
            $('#approve_client_name').text(clientName);
            $('#approve_client_form').attr('action', '{{url("client")}}/' + clientId + '/approve');
            $('#approve_client_modal').modal('show');
        });
        
        // Handle Reject Client button
        $(document).on('click', '.reject-btn', function() {
            var btn = $(this);
            var clientId = btn.data('client-id');
            var clientName = btn.data('client-name');
            
            $('#reject_client_name').text(clientName);
            $('#reject_client_form').attr('action', '{{url("client")}}/' + clientId + '/reject');
            $('#reject_client_modal').modal('show');
        });

        // Handle Generate Savings Account button
        $(document).on('click', '.generate-savings-btn', function() {
            var btn = $(this);
            var clientId = btn.data('client-id');
            var clientName = btn.data('client-name');
            
            if (!confirm('Generate default savings account for ' + clientName + '?')) {
                return;
            }
            
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Generating...');
            
            $.ajax({
                url: '{{url("client")}}/' + clientId + '/generate-savings-account',
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message || 'Failed to generate savings account');
                        btn.prop('disabled', false);
                        btn.html('<i class="fas fa-plus-circle"></i> Generate Account');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Error generating savings account';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                    btn.prop('disabled', false);
                    btn.html('<i class="fas fa-plus-circle"></i> Generate Account');
                }
            });
        });

        // Handle Assign Field Officer button
        var selectedClientIdForFieldOfficer = null;
        
        $(document).on('click', '.assign-field-officer-btn', function() {
            var btn = $(this);
            selectedClientIdForFieldOfficer = btn.data('client-id');
            var clientName = btn.data('client-name');
            
            $('#field-officer-client-name').text(clientName);
            $('#field-officer-id').val('');
            $('#assignFieldOfficerModal').modal('show');
        });
        
        // Save field officer assignment
        $('#save-field-officer-btn').on('click', function() {
            var fieldOfficerId = $('#field-officer-id').val();
            var $btn = $(this);
            
            if (!fieldOfficerId) {
                alert('Please select a field officer');
                return;
            }
            
            // Show loading state
            $btn.prop('disabled', true);
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Assigning...');
            
            $.ajax({
                url: '{{url("field-agent/client-assignments/update")}}',
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    client_id: selectedClientIdForFieldOfficer,
                    field_agent_id: fieldOfficerId
                },
                success: function(response) {
                    $('#assignFieldOfficerModal').modal('hide');
                    toastr.success(response.message || 'Field officer assigned successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    var errorMsg = 'Error assigning field officer';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                },
                complete: function() {
                    // Reset button state
                    $btn.prop('disabled', false);
                    $btn.html('<i class="fas fa-save"></i> Assign Field Officer');
                }
            });
        });

        // Bulk Approval Functionality
        var selectedClients = [];
        
        // Select All checkbox
        $('#selectAll').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.client-checkbox').prop('checked', isChecked);
            updateSelectedClients();
        });
        
        // Individual checkbox
        $(document).on('change', '.client-checkbox', function() {
            updateSelectedClients();
            
            // Update select all checkbox
            var totalCheckboxes = $('.client-checkbox').length;
            var checkedCheckboxes = $('.client-checkbox:checked').length;
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        });
        
        function updateSelectedClients() {
            selectedClients = [];
            $('.client-checkbox:checked').each(function() {
                selectedClients.push({
                    id: $(this).val(),
                    name: $(this).data('name'),
                    mobile: $(this).data('mobile'),
                    account: $(this).data('account')
                });
            });
            
            var count = selectedClients.length;
            $('#selectedCount').text(count);
            
            if (count > 0) {
                $('#bulkApproveBtn').show();
            } else {
                $('#bulkApproveBtn').hide();
            }
        }
        
        // Show selected clients in modal
        $('#bulkApproveModal').on('show.bs.modal', function() {
            var clientsList = '';
            selectedClients.forEach(function(client) {
                clientsList += '<li style="margin-bottom: 10px;">';
                clientsList += '<strong>' + client.name + '</strong><br>';
                clientsList += '<small class="text-muted">';
                clientsList += '<i class="fas fa-phone"></i> ' + (client.mobile || 'N/A') + ' &nbsp;&nbsp;';
                clientsList += '<i class="fas fa-university"></i> ' + (client.account || 'N/A');
                clientsList += '</small>';
                clientsList += '</li>';
            });
            $('#selectedClientsList').html(clientsList);
            $('#bulkSelectedCount').text(selectedClients.length);
        });
        
        // Handle bulk approval form submission
        $('#bulkApproveForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serializeArray();
            selectedClients.forEach(function(client) {
                formData.push({name: 'client_ids[]', value: client.id});
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $.param(formData),
                success: function(response) {
                    $('#bulkApproveModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Failed to approve clients'));
                }
            });
        });
    </script>
@endsection
