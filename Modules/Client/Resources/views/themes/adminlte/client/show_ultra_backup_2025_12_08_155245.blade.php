@extends('core::layouts.master')
@section('title')
    {{ $client->first_name }} {{ $client->last_name }}
@endsection
@section('styles')
<style>
/* Modern Banking Styles */
.client-hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.status-badge-modern {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 15px;
}

.status-pending { background: #f39c12; }
.status-active { background: #27ae60; }
.status-rejected { background: #e74c3c; }
.status-inactive { background: #95a5a6; }

.stat-card-modern {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: none;
}

.stat-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin: 10px 0;
}

.stat-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

.stat-icon {
    font-size: 40px;
    color: #3498db;
    margin-bottom: 10px;
}

.info-card-modern {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    border: none;
}

.btn-banking-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    color: white;
}

.btn-banking-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-banking-success {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    color: white;
}

.btn-banking-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
    color: white;
}

.btn-banking-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    color: white;
}

.btn-banking-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
    color: white;
}

.approval-info-card {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 5px solid #27ae60;
}

.rejection-info-card {
    background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 5px solid #e74c3c;
}

.pending-alert-card {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 5px solid #f39c12;
}

@media (max-width: 768px) {
    .stat-card-modern {
        margin-bottom: 15px;
    }
    .stat-value {
        font-size: 24px;
    }
}
</style>
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ $client->first_name }} {{ $client->last_name }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('client')}}">{{ trans_choice('client::general.client',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $client->first_name }} {{ $client->last_name }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-piggy-bank"></i></div>
                <div class="stat-value">
                    @php
                        $total_savings = \Modules\Savings\Entities\Savings::where('client_id', $client->id)->sum('balance_derived');
                    @endphp
                    {{ number_format($total_savings, 2) }}
                </div>
                <div class="stat-label">Total Savings</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="stat-value">
                    @php
                        $active_loans = \Modules\Loan\Entities\Loan::where('client_id', $client->id)->whereIn('status', ['active', 'disbursed'])->count();
                    @endphp
                    {{ $active_loans }}
                </div>
                <div class="stat-label">Active Loans</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-university"></i></div>
                <div class="stat-value">
                    @php
                        $total_accounts = \Modules\Savings\Entities\Savings::where('client_id', $client->id)->count();
                    @endphp
                    {{ $total_accounts }}
                </div>
                <div class="stat-label">Accounts</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-value">
                    {{ \Carbon\Carbon::parse($client->created_date)->format('M Y') }}
                </div>
                <div class="stat-label">Member Since</div>
            </div>
        </div>
    </div>

    <!-- Status-Based Alert Cards -->
    @if($client->status == 'pending')
    <div class="pending-alert-card">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x mr-3" style="color: #f39c12;"></i>
            <div class="flex-grow-1">
                <h5 class="mb-1" style="color: #f39c12; font-weight: 700;">Pending Approval</h5>
                <p class="mb-0" style="color: #7f8c8d;">This client is awaiting approval. Please review and approve or reject.</p>
            </div>
            @can('client.clients.activate')
            <div>
                <button class="btn btn-banking-success mr-2" data-toggle="modal" data-target="#approve_client_modal">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button class="btn btn-banking-danger" data-toggle="modal" data-target="#reject_client_modal">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
            @endcan
        </div>
    </div>
    @endif

    @if($client->status == 'active' && $client->approved_on_date)
    <div class="approval-info-card">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x mr-3" style="color: #27ae60;"></i>
            <div>
                <h5 class="mb-1" style="color: #27ae60; font-weight: 700;">Approved Client</h5>
                <p class="mb-0" style="color: #7f8c8d;">
                    Approved by <strong>{{ $client->approved_by_user->first_name ?? 'System' }} {{ $client->approved_by_user->last_name ?? '' }}</strong> 
                    on <strong>{{ \Carbon\Carbon::parse($client->approved_on_date)->format('d M Y') }}</strong>
                    @if($client->approved_notes)
                        <br><em>"{{ $client->approved_notes }}"</em>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    @if($client->status == 'rejected')
    <div class="rejection-info-card">
        <div class="d-flex align-items-center">
            <i class="fas fa-times-circle fa-2x mr-3" style="color: #e74c3c;"></i>
            <div class="flex-grow-1">
                <h5 class="mb-1" style="color: #e74c3c; font-weight: 700;">Rejected Client</h5>
                <p class="mb-0" style="color: #7f8c8d;">
                    Rejected by <strong>{{ $client->rejected_by_user->first_name ?? 'System' }} {{ $client->rejected_by_user->last_name ?? '' }}</strong> 
                    on <strong>{{ \Carbon\Carbon::parse($client->rejected_on_date)->format('d M Y') }}</strong>
                    @if($client->rejected_notes)
                        <br><strong>Reason:</strong> <em>"{{ $client->rejected_notes }}"</em>
                    @endif
                </p>
            </div>
            @can('client.clients.activate')
            <div>
                <a href="{{url('client/' . $client->id . '/undo_rejection')}}" class="btn btn-banking-primary confirm">
                    <i class="fas fa-undo"></i> Undo Rejection
                </a>
            </div>
            @endcan
        </div>
    </div>
    @endif

        <div class="row">
            <div class="col-md-3">
                <div class="card card-bordered card-preview">
                    <div class="card-body box-profile">

                        <div class="text-center">
                            @if(!empty($client->photo))
                                <a href="{{asset('storage/uploads/clients/'.$client->photo)}}"
                                   class="fancybox">
                                    <img
                                            class="profile-user-img img-fluid img-circle"
                                            src="{{asset('storage/uploads/clients/'.$client->photo)}}"
                                            alt="User profile picture">
                                </a>
                            @else
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{asset('themes/adminlte/img/user.png')}}"
                                     alt="User profile picture">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">
                            @if(!empty($client->title))
                                {{$client->title->name}}
                            @endif
                            {{$client->name}}
                        </h3>
                        @if(!empty($client->profession->name))
                            <p class="text-muted text-center">{{$client->profession->name}}</p>
                        @endif
                        <p class="text-muted text-center">#{{$client->id}}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.branch',1)}}
                                </b>
                                <a class="float-right">
                                    @if(!empty($client->branch))
                                        {{$client->branch->name}}
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.status',1)}}
                                </b>
                                <a class="float-right">
                                    <a class="float-right" data-toggle="modal"
                                       data-target="#change_status_modal" href="#">
                                        @if($client->status=='pending')
                                            {{trans_choice('core::general.pending',1)}}
                                        @endif
                                        @if($client->status=='active')
                                            {{trans_choice('core::general.active',1)}}
                                        @endif
                                        @if($client->status=='inactive')
                                            {{trans_choice('core::general.inactive',1)}}
                                        @endif
                                        @if($client->status=='deceased')
                                            {{trans_choice('core::general.deceased',1)}}
                                        @endif
                                        @if($client->status=='other')
                                            {{trans_choice('core::general.other',1)}}
                                        @endif
                                        @if($client->status=='closed')
                                            {{trans_choice('core::general.closed',1)}}
                                        @endif
                                    </a>
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.external_id',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->external_id}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.type',1)}}
                                </b>
                                <a class="float-right">
                                    @if(!empty($client->client_type))
                                        {{$client->client_type->name}}
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.staff',1)}}
                                </b>
                                <a class="float-right">
                                    @if(!empty($client->loan_officer))
                                        {{$client->loan_officer->first_name}} {{$client->loan_officer->last_name}}
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.mobile',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->mobile}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.email',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->email}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.dob',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->dob}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.gender',1)}}
                                </b>
                                <a class="float-right">
                                    @if($client->gender=='male')
                                        {{trans_choice('core::general.male',1)}}
                                    @endif
                                    @if($client->gender=='female')
                                        {{trans_choice('core::general.female',1)}}
                                    @endif
                                    @if($client->gender=='unspecified')
                                        {{trans_choice('core::general.unspecified',1)}}
                                    @endif
                                    @if($client->gender=='other')
                                        {{trans_choice('core::general.other',1)}}
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('client::general.marital_status',1)}}
                                </b>
                                <a class="float-right">
                                    @if($client->marital_status=='single')
                                        {{trans_choice('client::general.single',1)}}
                                    @endif
                                    @if($client->marital_status=='married')
                                        {{trans_choice('client::general.married',1)}}
                                    @endif
                                    @if($client->marital_status=='divorced')
                                        {{trans_choice('client::general.divorced',1)}}
                                    @endif
                                    @if($client->marital_status=='widowed')
                                        {{trans_choice('client::general.widowed',1)}}
                                    @endif
                                    @if($client->marital_status=='other')
                                        {{trans_choice('client::general.other',1)}}
                                    @endif
                                    @if($client->marital_status=='unspecified')
                                        {{trans_choice('core::general.unspecified',1)}}
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.zip',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->zip}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.joined_date',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->created_date}}
                                </a>
                            </li>
                            @foreach($custom_fields as $custom_field)
                                <?php
                                $field = custom_field_build_form_field($custom_field, $client->id);
                                ?>
                                <li class="list-group-item">
                                    <b>
                                        {{$field['label']}}
                                    </b>
                                    <a class="float-right">
                                        @if($custom_field->type=='checkbox')
                                            @foreach(explode(',',$field['current'] ) as $key)
                                                {{$key}}<br>
                                            @endforeach
                                        @else
                                            {{$field['current'] }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                            <li class="list-group-item">
                                <b>
                                    {{trans_choice('core::general.activation_date',1)}}
                                </b>
                                <a class="float-right">
                                    {{$client->activation_date}}
                                </a>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center">
                            @can('client.clients.activate')
                                <a href="#" data-toggle="modal" class="btn btn-primary btn-sm  m-1"
                                   data-target="#change_status_modal">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{trans_choice('client::general.change',1)}} {{trans_choice('core::general.status',1)}}</span>
                                </a>
                            @endcan
                            @can('client.clients.edit')
                                <a href="{{url('client/' . $client->id . '/edit')}}"
                                   class="btn btn-primary btn-sm  m-1">
                                    <i class="fas fa-edit"></i>
                                    <span>{{trans_choice('core::general.edit',1)}}</span>
                                </a>

                                <a href="#" data-toggle="modal"
                                   data-target="#transfer_client_modal" class="btn btn-primary btn-sm m-1"><i
                                            class="fas fa-forward"></i>
                                    <span>{{trans_choice('client::general.transfer',1)}}</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> {{trans_choice('core::general.address',1)}}
                        </strong>

                        <p class="text-muted">
                            {{$client->address}}<br>
                            @if(!empty($client->country))
                                {{$client->country->name}}
                            @endif
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> {{trans_choice('core::general.note',2)}}</strong>

                        <p class="text-muted"> {{$client->notes}}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#accounts" data-toggle="tab"
                                   aria-expanded="false">{{trans_choice('client::general.account',2)}}
                                </a>
                            </li>
                            @can('client.clients.identification.index')
                                <li class="nav-item">
                                    <a class="nav-link" href="#client_identification" data-toggle="tab"
                                       aria-expanded="false">{{trans_choice('client::general.identification',1)}}</a>
                                </li>
                            @endcan
                            @can('client.clients.next_of_kin.index')
                                <li class="nav-item">
                                    <a class="nav-link" href="#client_next_of_kin" data-toggle="tab"
                                       aria-expanded="true">{{trans_choice('client::general.next_of_kin',1)}}</a>
                                </li>
                            @endcan
                            @can('client.clients.index')
                                <li class="nav-item">
                                    <a class="nav-link" href="#login_details" data-toggle="tab"
                                       aria-expanded="false">{{trans_choice('user::general.login',1)}} {{trans_choice('core::general.detail',2)}}</a>
                                </li>
                            @endcan
                            @can('client.clients.files.index')
                                <li class="nav-item">
                                    <a class="nav-link" href="#files" data-toggle="tab"
                                       aria-expanded="false">{{trans_choice('client::general.file',2)}}</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="accounts">
                                @can('loan.loans.index')
                                    <h4>{{ trans_choice('loan::general.loan',2) }}</h4>
                                    <table class="table  table-striped table-hover table-condensed"
                                           id="loan-data-table">
                                        <thead>
                                        <tr>
                                            <th>{{ trans_choice('core::general.id',1) }}</th>
                                            <th>{{ trans_choice('core::general.amount',1) }}</th>
                                            <th>{{ trans_choice('loan::general.balance',1) }}</th>
                                            <th>{{ trans('loan::general.disbursed') }}</th>
                                            <th>{{ trans_choice('loan::general.status',1) }}</th>
                                            <th>{{ trans_choice('loan::general.product',1) }}</th>
                                            <th>{{ trans_choice('core::general.action',1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                @endcan
                                @can('savings.savings.index')
                                    <h4>{{ trans_choice('savings::general.savings',2) }}</h4>
                                    <table class="table  table-striped table-hover table-condensed"
                                           id="savings-data-table">
                                        <thead>
                                        <tr>
                                            <th>{{ trans_choice('core::general.id',1) }}</th>
                                            <th>{{ trans_choice('savings::general.interest_rate',1) }}</th>
                                            <th>{{ trans_choice('savings::general.balance',1) }}</th>
                                            <th>{{ trans_choice('savings::general.status',1) }}</th>
                                            <th>{{ trans_choice('savings::general.product',1) }}</th>
                                            <th>{{ trans_choice('core::general.action',1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                @endcan
                            </div>
                            @can('client.clients.identification.index')
                                <div class="tab-pane" id="client_identification">
                                    <div>
                                        @can('client.clients.identification.create')
                                            <a href="{{url('client/'.$client->id.'/client_identification/create')}}"
                                               class="btn btn-info float-right mb-2">{{trans_choice('core::general.add',1)}} {{trans_choice('client::general.identification',1)}}</a>
                                        @endcan
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{ trans_choice('core::general.type',1) }}</th>
                                                <th>{{ trans_choice('core::general.id',1) }}</th>
                                                <th>{{ trans_choice('client::general.status',1) }}</th>
                                                <th>{{ trans_choice('core::general.description',1) }}</th>
                                                <th>{{ trans_choice('core::general.action',1) }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($client->identifications as $key)
                                                <tr>
                                                    <td>
                                                        @if(!empty($key->identification_type))
                                                            {{$key->identification_type->name}}
                                                        @endif
                                                    </td>
                                                    <td>{{$key->identification_value}}</td>
                                                    <td>
                                                        @if($key->status==='pending')
                                                            <span class="badge badge-warning">{{trans_choice('client::general.pending',1)}}</span>
                                                        @endif
                                                        @if($key->status==='approved')
                                                            <span class="badge badge-success">{{trans_choice('client::general.approved',1)}}</span>
                                                        @endif
                                                        @if($key->status==='rejected')
                                                            <span class="badge badge-danger">{{trans_choice('client::general.rejected',1)}}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$key->description}}</td>
                                                    <td>
                                                        <a href="{{asset('storage/uploads/clients/'.$key->link)}}"
                                                           target="_blank"><i class="fa fa-download"></i> </a>
                                                        @can('client.clients.identification.edit')
                                                            <a href="{{url('client/client_identification/'.$key->id.'/edit')}}"><i
                                                                        class="fas fa-edit"></i> </a>
                                                        @endcan
                                                        @can('client.clients.identification.destroy')
                                                            <a href="{{url('client/client_identification/'.$key->id.'/destroy')}}"
                                                               class="confirm"><i class="fas fa-trash"></i> </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            @endcan
                        <!-- /.tab-pane -->
                            @can('client.clients.next_of_kin.index')
                                <div class="tab-pane" id="client_next_of_kin">
                                    @can('client.clients.next_of_kin.create')
                                        <a href="{{url('client/'.$client->id.'/client_next_of_kin/create')}}"
                                           class="btn btn-info float-right mb-2">{{trans_choice('core::general.add',1)}} {{trans_choice('client::general.next_of_kin',1)}}</a>
                                    @endcan
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>{{ trans_choice('core::general.name',1) }}</th>
                                            <th>{{ trans('core::general.gender') }}</th>
                                            <th>{{ trans('core::general.dob') }}</th>
                                            <th>{{ trans('core::general.mobile') }}</th>
                                            <th>{{ trans_choice('core::general.email',1) }}</th>
                                            <th>{{ trans_choice('client::general.profession',1) }}</th>
                                            <th>{{ trans_choice('client::general.relationship',1) }}</th>
                                            <th>{{ trans_choice('core::general.address',1) }}</th>
                                            <th>{{ trans_choice('core::general.action',1) }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client->next_of_kins as $key)
                                            <tr>
                                                <td>{{$key->first_name}} {{$key->last_name}}</td>
                                                <td>
                                                    @if($key->gender=='male')
                                                        {{trans_choice('core::general.male',1)}}
                                                    @endif
                                                    @if($key->gender=='female')
                                                        {{trans_choice('core::general.female',1)}}
                                                    @endif
                                                    @if($key->gender=='unspecified')
                                                        {{trans_choice('core::general.unspecified',1)}}
                                                    @endif
                                                    @if($key->gender=='other')
                                                        {{trans_choice('core::general.other',1)}}
                                                    @endif
                                                </td>
                                                <td>{{$key->dob}}</td>
                                                <td>{{$key->mobile}}</td>
                                                <td>{{$key->email}}</td>
                                                <td>
                                                    @if(!empty($key->profession))
                                                        {{$key->profession->name}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($key->client_relationship))
                                                        {{$key->client_relationship->name}}
                                                    @endif
                                                </td>

                                                <td>{{$key->address}}</td>
                                                <td>
                                                    @can('client.clients.next_of_kin.edit')
                                                        <a href="{{url('client/client_next_of_kin/'.$key->id.'/edit')}}"><i
                                                                    class="fas fa-edit"></i> </a>
                                                    @endcan
                                                    @can('client.clients.next_of_kin.destroy')
                                                        <a href="{{url('client/client_next_of_kin/'.$key->id.'/destroy')}}"
                                                           class="confirm"><i class="fas fa-trash"></i> </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endcan
                            <div class="tab-pane" id="login_details">
                                @can('client.clients.user.create')
                                    <a href="{{url('client/'.$client->id.'/user/create')}}"
                                       class="btn btn-info float-right mb-2">{{trans_choice('core::general.add',1)}} {{trans_choice('core::general.user',1)}}</a>
                                @endcan
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>{{ trans_choice('core::general.name',1) }}</th>
                                        <th>{{ trans_choice('core::general.email',1) }}</th>
                                        <th>{{ trans_choice('core::general.action',1) }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($client->client_users as $key)
                                        @if($key->user)
                                            <tr>
                                                <td>{{$key->user->first_name}} {{$key->user->last_name}}</td>
                                                <td>{{$key->user->email}}</td>
                                                <td>
                                                    @can('client.clients.user.create')
                                                        <a href="{{url('user/'.$key->user_id.'/edit')}}"
                                                           class=""><i class="fas fa-edit"></i> </a>
                                                    @endcan
                                                    @can('client.clients.user.destroy')
                                                        <a href="{{url('client/user/'.$key->id.'/destroy')}}"
                                                           class="confirm"><i class="fas fa-trash"></i> </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @can('client.clients.files.index')
                                <div class="tab-pane" id="files">
                                    <div>
                                        @can('client.clients.files.index')
                                            <a href="{{url('client/'.$client->id.'/file/create')}}"
                                               class="btn btn-info float-right mb-2">{{trans_choice('core::general.add',1)}} {{trans_choice('client::general.file',1)}}</a>
                                        @endcan
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{ trans_choice('core::general.name',1) }}</th>
                                                <th>{{ trans_choice('client::general.status',1) }}</th>
                                                <th>{{ trans_choice('core::general.description',1) }}</th>
                                                <th>{{ trans_choice('core::general.action',1) }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($client->files as $key)
                                                <tr>
                                                    <td>{{$key->name}}</td>
                                                    <td>
                                                        @if($key->status==='pending')
                                                            <span class="badge badge-warning">{{trans_choice('client::general.pending',1)}}</span>
                                                        @endif
                                                        @if($key->status==='approved')
                                                            <span class="badge badge-success">{{trans_choice('client::general.approved',1)}}</span>
                                                        @endif
                                                        @if($key->status==='rejected')
                                                            <span class="badge badge-danger">{{trans_choice('client::general.rejected',1)}}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$key->description}}</td>
                                                    <td>
                                                        <a href="{{asset('storage/uploads/clients/'.$key->link)}}"
                                                           target="_blank"><i class="fa fa-download"></i> </a>
                                                        @can('client.clients.files.edit')
                                                            <a href="{{url('client/file/'.$key->id.'/edit')}}"><i
                                                                        class="fas fa-edit"></i> </a>
                                                        @endcan
                                                        @can('client.clients.files.destroy')
                                                            <a href="{{url('client/file/'.$key->id.'/destroy')}}"
                                                               class="confirm"><i class="fas fa-trash"></i> </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="modal fade in" id="change_status_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans_choice('client::general.change',1) }} {{ trans_choice('core::general.status',1) }}</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span></button>
                    </div>
                    <form method="post"
                          action="{{ url('client/'.$client->id.'/change_status') }}"
                          class="form-horizontal">
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status"
                                       class="control-label">{{ trans_choice('core::general.status',1) }}</label>
                                <select class="form-control" name="status" id="status">
                                    <option value=""></option>
                                    <option value="pending"
                                            @if($client->status=="pending") selected @endif>{{trans_choice("client::general.pending",1)}}</option>
                                    <option value="active"
                                            @if($client->status=="active") selected @endif>{{trans_choice("client::general.active",1)}}</option>
                                    <option value="inactive"
                                            @if($client->status=="inactive") selected @endif>{{trans_choice("client::general.inactive",1)}}</option>
                                    <option value="closed"
                                            @if($client->status=="closed") selected @endif>{{trans_choice("client::general.closed",1)}}</option>
                                    <option value="deceased"
                                            @if($client->status=="deceased") selected @endif>{{trans_choice("client::general.deceased",1)}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_date"
                                       class="control-label">{{ trans_choice('core::general.date',1) }}</label>
                                <input type="text" name="date"
                                       class="form-control date-picker"
                                       value="{{date("Y-m-d")}}"
                                       required=""
                                       id="status_date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default float-left"
                                    data-dismiss="modal">
                                {{ trans_choice('core::general.close',1) }}
                            </button>
                            <button type="submit"
                                    class="btn btn-primary float-right">{{ trans_choice('core::general.save',1) }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')

    <script>
        $(document).ready(function () {


            $('#savings-data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('savings/get_savings?client_id='.$client->id) !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'interest_rate', name: 'interest_rate'},
                    {data: 'balance', name: 'balance'},
                    {data: 'status', name: 'status'},
                    {data: 'savings_product', name: 'savings_products.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "order": [[0, "desc"]],
                "language": {
                    "lengthMenu": "{{ trans('general.lengthMenu') }}",
                    "zeroRecords": "{{ trans('general.zeroRecords') }}",
                    "info": "{{ trans('general.info') }}",
                    "infoEmpty": "{{ trans('general.infoEmpty') }}",
                    "search": "{{ trans('general.search') }}",
                    "infoFiltered": "{{ trans('general.infoFiltered') }}",
                    "paginate": {
                        "first": "{{ trans('general.first') }}",
                        "last": "{{ trans('general.last') }}",
                        "next": "{{ trans('general.next') }}",
                        "previous": "{{ trans('general.previous') }}"
                    }
                },
                responsive: false,
                "autoWidth": false,
                "drawCallback": function (settings) {
                    $('.confirm').on('click', function (e) {
                        e.preventDefault();
                        var href = $(this).attr('href');
                        swal({
                            title: 'Are you sure?',
                            text: '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok',
                            cancelButtonText: 'Cancel'
                        }).then(function () {
                            window.location = href;
                        })
                    });
                }
            });
            $('#loan-data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('loan/get_loans?client_id='.$client->id) !!}',
                columns: [
                    {data: 'id', name: 'id', render: function(data) { return data; }},
                    {data: 'principal', name: 'principal', render: function(data) { return data; }},
                    {data: 'balance', name: 'balance', orderable: false, render: function(data) { return data; }},
                    {data: 'disbursed_on_date', name: 'disbursed_on_date'},
                    {data: 'status', name: 'status', render: function(data) { return data; }},
                    {data: 'loan_product', name: 'loan_products.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, render: function(data) { return data; }}
                ],
                "order": [[0, "desc"]],
                "language": {
                    "lengthMenu": "{{ trans('general.lengthMenu') }}",
                    "zeroRecords": "{{ trans('general.zeroRecords') }}",
                    "info": "{{ trans('general.info') }}",
                    "infoEmpty": "{{ trans('general.infoEmpty') }}",
                    "search": "{{ trans('general.search') }}",
                    "infoFiltered": "{{ trans('general.infoFiltered') }}",
                    "paginate": {
                        "first": "{{ trans('general.first') }}",
                        "last": "{{ trans('general.last') }}",
                        "next": "{{ trans('general.next') }}",
                        "previous": "{{ trans('general.previous') }}"
                    }
                },
                responsive: false,
                "drawCallback": function (settings) {
                    $('.confirm').on('click', function (e) {
                        e.preventDefault();
                        var href = $(this).attr('href');
                        swal({
                            title: 'Are you sure?',
                            text: '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok',
                            cancelButtonText: 'Cancel'
                        }).then(function () {
                            window.location = href;
                        })
                    });
                }
            });
        })
    </script>

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
                <form method="POST" action="{{url('client/' . $client->id . '/approve')}}">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to approve <strong>{{ $client->name }}</strong>?</p>
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
                        <button type="submit" class="btn btn-banking-success">
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
                <form method="POST" action="{{url('client/' . $client->id . '/reject')}}">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to reject <strong>{{ $client->name }}</strong>?</p>
                        <div class="form-group">
                            <label for="rejected_notes">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejected_notes" id="rejected_notes" class="form-control" rows="4" placeholder="Enter reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-banking-danger">
                            <i class="fas fa-times"></i> Reject Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
