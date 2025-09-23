@extends('core::layouts.master')
@section('title')
    {{ trans_choice('client::general.group',1) }} - {{$group->name}}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('client::general.group',1) }} - {{$group->name}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('client/group')}}">{{ trans_choice('client::general.group',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{$group->name}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-8">
                <!-- Group Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans_choice('client::general.group',1) }} {{ trans_choice('core::general.details',1) }}</h3>
                        <div class="card-tools">
                            @can('client.groups.edit')
                                <a href="{{url('client/group/'.$group->id.'/edit')}}" class="btn btn-tool">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>{{trans_choice('core::general.name',1)}}:</strong></td>
                                        <td>{{$group->name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{trans_choice('core::general.branch',1)}}:</strong></td>
                                        <td>{{$group->branch ? $group->branch->name : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{trans_choice('loan::general.loan_officer',1)}}:</strong></td>
                                        <td>{{$group->loan_officer ? $group->loan_officer->first_name . ' ' . $group->loan_officer->last_name : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Meeting Frequency:</strong></td>
                                        <td>{{ucfirst($group->meeting_frequency)}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Meeting Day:</strong></td>
                                        <td>{{$group->meeting_day ?: '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{trans_choice('core::general.status',1)}}:</strong></td>
                                        <td>
                                            @if($group->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($group->status == 'inactive')
                                                <span class="badge badge-warning">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Members:</strong></td>
                                        <td>{{$group->member_count}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Loans:</strong></td>
                                        <td>{{$group->total_loans}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if($group->description)
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>{{trans_choice('core::general.description',1)}}:</strong>
                                    <p>{{$group->description}}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Group Members Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans_choice('client::general.members',2) }}</h3>
                        <div class="card-tools">
                            @can('client.groups.manage_members')
                                @if($group->loans()->where('status', 'active')->exists())
                                    <button type="button" class="btn btn-tool" disabled title="Cannot manage members while group has active loans">
                                        <i class="fas fa-plus"></i> Add Member
                                    </button>
                                @else
                                    <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#addMemberModal">
                                        <i class="fas fa-plus"></i> Add Member
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ trans_choice('core::general.name',1) }}</th>
                                    <th>Role</th>
                                    <th>Joined Date</th>
                                    <th>{{ trans_choice('core::general.status',1) }}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($group->active_members as $member)
                                    <tr>
                                        <td>
                                            <a href="{{url('client/'.$member->client->id.'/show')}}">
                                                {{$member->client->name}}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ucfirst($member->role)}}</span>
                                        </td>
                                        <td>{{$member->joined_at ? $member->joined_at->format('Y-m-d') : '-'}}</td>
                                        <td>
                                            <span class="badge badge-success">Active</span>
                                        </td>
                                        <td>
                                            @can('client.groups.manage_members')
                                                @if($group->loans()->where('status', 'active')->exists())
                                                    <span class="text-muted" title="Cannot manage members while group has active loans">
                                                        <i class="fas fa-lock"></i> Locked
                                                    </span>
                                                @else
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#" onclick="editMemberRole({{$member->id}}, '{{$member->role}}')">
                                                                <i class="fas fa-edit"></i> Edit Role
                                                            </a>
                                                            <a class="dropdown-item text-danger" href="{{url('client/group/'.$group->id.'/members/'.$member->id.'/remove')}}" 
                                                               onclick="return confirm('Are you sure you want to remove this member?')">
                                                                <i class="fas fa-trash"></i> Remove
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No members found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Group Loans Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans_choice('loan::general.loans',2) }}</h3>
                        <div class="card-tools">
                            @can('loan.loans.create')
                                <a href="{{url('loan/create?group_id='.$group->id)}}" class="btn btn-tool">
                                    <i class="fas fa-plus"></i> New Group Loan
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Amount</th>
                                    <th>{{ trans_choice('core::general.status',1) }}</th>
                                    <th>Disbursed Date</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($group->loans as $loan)
                                    <tr>
                                        <td>
                                            <a href="{{url('loan/'.$loan->id.'/show')}}">
                                                #{{$loan->id}}
                                            </a>
                                        </td>
                                        <td>{{number_format($loan->principal, 2)}}</td>
                                        <td>
                                            <span class="badge badge-{{$loan->status == 'active' ? 'success' : 'warning'}}">
                                                {{ucfirst($loan->status)}}
                                            </span>
                                        </td>
                                        <td>{{$loan->disbursed_on_date ?: '-'}}</td>
                                        <td>
                                            <a href="{{url('loan/'.$loan->id.'/show')}}" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No loans found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Quick Stats Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Stats</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active Members</span>
                                <span class="info-box-number">{{$group->member_count}}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Loans</span>
                                <span class="info-box-number">{{$group->total_loans}}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active Loan Balance</span>
                                <span class="info-box-number">{{number_format($group->active_loan_balance, 2)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Member Modal -->
        @can('client.groups.manage_members')
        <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="{{url('client/group/'.$group->id.'/members')}}">
                        {{csrf_field()}}
                        <div class="modal-header">
                            <h4 class="modal-title">Add Group Member</h4>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="client_id">Select Client</label>
                                <select name="client_id" id="client_id" class="form-control select2" required>
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}">{{$client->first_name}} {{$client->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="member">Member</option>
                                    <option value="leader">Leader</option>
                                    <option value="treasurer">Treasurer</option>
                                    <option value="secretary">Secretary</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for client dropdown in modal
            $('#client_id').select2({
                placeholder: 'Search and select a client...',
                allowClear: true,
                dropdownParent: $('#addMemberModal')
            });
        });

        function editMemberRole(memberId, currentRole) {
            var newRole = prompt('Enter new role (member, leader, treasurer, secretary):', currentRole);
            if (newRole && newRole !== currentRole) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{url("client/group/".$group->id."/members")}}/' + memberId + '/update';
                
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{csrf_token()}}';
                
                var roleInput = document.createElement('input');
                roleInput.type = 'hidden';
                roleInput.name = 'role';
                roleInput.value = newRole;
                
                form.appendChild(csrfToken);
                form.appendChild(roleInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
