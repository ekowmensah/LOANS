@extends('core::layouts.master')
@section('title')
    {{ $branch->name }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ $branch->name }}
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
                                    href="{{url('branch')}}">{{ trans_choice('core::general.branch',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $branch->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        
        <!-- Statistics Cards -->
        <div class="row">
            <!-- Clients Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($total_clients) }}</h3>
                        <p>Total Clients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ url('client') }}?branch_id={{ $branch->id }}" class="small-box-footer">
                        Active: {{ number_format($active_clients) }} | Inactive: {{ number_format($inactive_clients) }}
                    </a>
                </div>
            </div>
            
            <!-- Groups Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($total_groups) }}</h3>
                        <p>Total Groups</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <a href="{{ url('group') }}?branch_id={{ $branch->id }}" class="small-box-footer">
                        Active: {{ number_format($active_groups) }}
                    </a>
                </div>
            </div>
            
            <!-- Loans Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($loan_stats->total_loans) }}</h3>
                        <p>Total Loans</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <a href="{{ url('loan') }}?branch_id={{ $branch->id }}" class="small-box-footer">
                        Active: {{ number_format($loan_stats->active_loans) }}
                    </a>
                </div>
            </div>
            
            <!-- Savings Card -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($savings_stats->total_savings) }}</h3>
                        <p>Total Savings</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <a href="{{ url('savings') }}?branch_id={{ $branch->id }}" class="small-box-footer">
                        Active: {{ number_format($savings_stats->active_savings) }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Loan Portfolio Overview -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line"></i> Loan Portfolio Overview</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Principal Disbursed</strong></td>
                                        <td class="text-right">{{ number_format($loan_stats->principal_disbursed, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Principal Repaid</strong></td>
                                        <td class="text-right text-success">{{ number_format($loan_stats->principal_repaid, 2) }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Principal Outstanding</strong></td>
                                        <td class="text-right"><strong>{{ number_format($loan_stats->principal_outstanding, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Principal Written Off</strong></td>
                                        <td class="text-right text-danger">{{ number_format($loan_stats->principal_written_off, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Interest Disbursed</strong></td>
                                        <td class="text-right">{{ number_format($loan_stats->interest_disbursed, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Interest Repaid</strong></td>
                                        <td class="text-right text-success">{{ number_format($loan_stats->interest_repaid, 2) }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Interest Outstanding</strong></td>
                                        <td class="text-right"><strong>{{ number_format($loan_stats->interest_outstanding, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Interest Waived</strong></td>
                                        <td class="text-right text-warning">{{ number_format($loan_stats->interest_waived, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Fees & Penalties</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Fees Disbursed</td>
                                        <td class="text-right">{{ number_format($loan_stats->fees_disbursed, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fees Repaid</td>
                                        <td class="text-right">{{ number_format($loan_stats->fees_repaid, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penalties Disbursed</td>
                                        <td class="text-right">{{ number_format($loan_stats->penalties_disbursed, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penalties Repaid</td>
                                        <td class="text-right">{{ number_format($loan_stats->penalties_repaid, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Loan Status Breakdown</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Pending</td>
                                        <td class="text-right">{{ number_format($loan_stats->pending_loans) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Approved</td>
                                        <td class="text-right">{{ number_format($loan_stats->approved_loans) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Active</td>
                                        <td class="text-right">{{ number_format($loan_stats->active_loans) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Closed</td>
                                        <td class="text-right">{{ number_format($loan_stats->closed_loans) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Written Off</td>
                                        <td class="text-right text-danger">{{ number_format($loan_stats->written_off_loans) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Portfolio Quality -->
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Portfolio at Risk (PAR)</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $par_ratio = $loan_stats->principal_outstanding > 0 
                                ? ($par_stats->par_amount / $loan_stats->principal_outstanding) * 100 
                                : 0;
                            $par_30_ratio = $loan_stats->principal_outstanding > 0 
                                ? ($par_stats->par_30 / $loan_stats->principal_outstanding) * 100 
                                : 0;
                            $par_90_ratio = $loan_stats->principal_outstanding > 0 
                                ? ($par_stats->par_90 / $loan_stats->principal_outstanding) * 100 
                                : 0;
                        @endphp
                        
                        <div class="mb-3">
                            <strong>Total PAR</strong>
                            <h4 class="text-danger">{{ number_format($par_stats->par_amount, 2) }}</h4>
                            <small>{{ number_format($par_ratio, 2) }}% of portfolio</small>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-2">
                            <strong>PAR > 30 Days</strong>
                            <div class="d-flex justify-content-between">
                                <span>{{ number_format($par_stats->par_30, 2) }}</span>
                                <span class="badge badge-warning">{{ number_format($par_30_ratio, 2) }}%</span>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <strong>PAR > 90 Days</strong>
                            <div class="d-flex justify-content-between">
                                <span>{{ number_format($par_stats->par_90, 2) }}</span>
                                <span class="badge badge-danger">{{ number_format($par_90_ratio, 2) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title"><i class="fas fa-clock"></i> Last 30 Days</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>New Applications</strong>
                            <h4>{{ number_format($recent_loan_applications) }}</h4>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-2">
                            <strong>Disbursements</strong>
                            <div class="d-flex justify-content-between">
                                <span>{{ number_format($recent_disbursements->count) }} loans</span>
                            </div>
                            <h5 class="text-success">{{ number_format($recent_disbursements->amount, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Savings Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-piggy-bank"></i> Savings Overview</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td><strong>Total Balance</strong></td>
                                <td class="text-right"><h4 class="text-success">{{ number_format($savings_stats->total_balance, 2) }}</h4></td>
                            </tr>
                            <tr>
                                <td>Total Deposits</td>
                                <td class="text-right">{{ number_format($savings_stats->total_deposits, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Withdrawals</td>
                                <td class="text-right">{{ number_format($savings_stats->total_withdrawals, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Interest Posted</td>
                                <td class="text-right">{{ number_format($savings_stats->total_interest_posted, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                            <tr>
                                <td>Active Accounts</td>
                                <td class="text-right">{{ number_format($savings_stats->active_savings) }}</td>
                            </tr>
                            <tr>
                                <td>Inactive Accounts</td>
                                <td class="text-right">{{ number_format($savings_stats->inactive_savings) }}</td>
                            </tr>
                            <tr>
                                <td>Closed Accounts</td>
                                <td class="text-right">{{ number_format($savings_stats->closed_savings) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Monthly Disbursement Trends (Last 6 Months)</h3>
                    </div>
                    <div class="card-body">
                        @if($monthly_trends->count() > 0)
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th class="text-right">Loans</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthly_trends as $trend)
                                        <tr>
                                            <td>{{ date('M Y', strtotime($trend->month.'-01')) }}</td>
                                            <td class="text-right">{{ number_format($trend->loan_count) }}</td>
                                            <td class="text-right">{{ number_format($trend->disbursed_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No disbursements in the last 6 months</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Branch Details & Users -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="box-title">{{ $branch->name }}</h6>

                        <div class="box-tools">

                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>{{ trans_choice('core::general.open',1) }} {{ trans_choice('core::general.date',1) }}</td>
                                <td>{{$branch->open_date}}</td>
                            </tr>
                            <tr>
                                <td>{{ trans_choice('core::general.active',1) }} </td>
                                <td>
                                    @if($branch->active==1)
                                        {{ trans_choice('core::general.yes',1) }}
                                    @else
                                        {{ trans_choice('core::general.no',1) }}
                                    @endif
                                </td>
                            </tr>
                            @foreach($custom_fields as $custom_field)
                                <?php
                                $field = custom_field_build_form_field($custom_field, $branch->id);
                                ?>
                                <tr>
                                    <td>{{$field['label']}}</td>
                                    <td>
                                        @if($custom_field->type=='checkbox')
                                            @foreach(explode(',',$field['current'] ) as $key)
                                                {{$key}}<br>
                                            @endforeach
                                        @else
                                            {{$field['current'] }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>{{ trans_choice('core::general.note',2) }} </td>
                                <td>{!! $branch->notes !!}</td>
                            </tr>
                        </table>

                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <div class="heading-elements">
                        <span class="heading-text">{{ trans_choice('core::general.created_at',1) }}
                            : {{$branch->created_at}}</span>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">{{ trans_choice('core::general.user',2) }}</h6>

                        <div class="card-tools pull-right">
                            @can('branch.branches.assign_user')
                                <a href="#" data-toggle="modal" data-target="#addUser"
                                   class="btn btn-info btn-sm">{{trans_choice('core::general.add',1)}} {{trans_choice('core::general.user',1)}}</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="" class="table table-striped basic-data-table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>{{trans_choice('core::general.id',1)}}</th>
                                    <th>{{trans_choice('core::general.name',1)}}</th>
                                    <th>{{trans_choice('core::general.phone',1)}}</th>
                                    <th>{{trans_choice('core::general.email',1)}}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($branch->users as $key)
                                    @if(!empty($key->user))
                                        <tr>
                                            <td>{{ $key->user->id }}</td>
                                            <td>{{ $key->user->first_name }} {{ $key->user->last_name }}</td>
                                            <td>{{ $key->user->phone }}</td>
                                            <td>{{ $key->user->email }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button href="#" class="btn btn-default dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('branch.branches.assign_user')
                                                            <a href="{{ url('user/'.$key->user->id.'/show') }}" class="dropdown-item"><i
                                                                        class="fa fa-search"></i> {{trans_choice('general.detail',2)}}
                                                            </a>

                                                            <a href="{{ url('user/'.$key->user->id.'/edit') }}" class="dropdown-item"><i
                                                                        class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                            </a>

                                                            <a href="{{ url('branch/'.$key->id.'/remove_user') }}"
                                                               class="dropdown-item confirm"><i
                                                                        class="fa fa-trash"></i> {{ trans('general.remove') }}
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>
                <!-- /.box -->
            </div>
        </div>
        <div class="modal fade" id="addUser">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{trans_choice('core::general.add',1)}} {{trans_choice('core::general.user',1)}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">*</span></button>
                    </div>
                    <form method="post" action="{{url('branch/'.$branch->id.'/add_user')}}" class="">
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label"
                                       for="user_id">{{trans_choice('core::general.select',1)}} {{trans_choice('core::general.user',1)}}</label>
                                <select class="form-control select2" name="user_id" id="user_id" required>
                                    <option value=""></option>
                                    @foreach(\Modules\User\Entities\User::all() as $key)
                                        <option value="{{$key->id}}">{{$key->first_name}} {{$key->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">{{trans_choice('core::general.save',1)}}</button>
                            <button type="button" class="btn default"
                                    data-dismiss="modal">{{trans_choice('core::general.close',1)}}</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </section>
@endsection