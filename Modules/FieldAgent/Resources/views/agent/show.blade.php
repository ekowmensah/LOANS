@extends('core::layouts.master')

@section('title')
    {{ trans_choice('fieldagent::general.field_agent', 1) }} - {{ $agent->agent_code }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('fieldagent::general.field_agent', 1) }} Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/agent') }}">{{ trans_choice('fieldagent::general.field_agent', 2) }}</a></li>
                        <li class="breadcrumb-item active">{{ $agent->agent_code }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <!-- Agent Info Card -->
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if($agent->photo)
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset($agent->photo) }}" alt="Agent photo">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/dist/img/avatar.png') }}" alt="Agent photo">
                            @endif
                        </div>

                        <h3 class="profile-username text-center">{{ $agent->full_name }}</h3>
                        <p class="text-muted text-center">{{ $agent->agent_code }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ trans_choice('core::general.branch', 1) }}</b>
                                <a class="float-right">{{ $agent->branch->name ?? 'N/A' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ trans_choice('core::general.status', 1) }}</b>
                                <span class="float-right">
                                    @if($agent->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif($agent->status == 'suspended')
                                        <span class="badge badge-warning">Suspended</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>{{ trans_choice('core::general.phone', 1) }}</b>
                                <a class="float-right">{{ $agent->phone_number ?? 'N/A' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Commission Rate</b>
                                <a class="float-right">{{ $agent->commission_rate }}%</a>
                            </li>
                        </ul>

                        @can('field_agent.agents.edit')
                            <a href="{{ url('field-agent/agent/' . $agent->id . '/edit') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-edit"></i> {{ trans_choice('core::general.edit', 1) }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $stats['total_collections'] }}</h3>
                                <p>This Month Collections</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ number_format($stats['total_amount'], 0) }}</h3>
                                <p>Total Amount</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $stats['pending_collections'] }}</h3>
                                <p>Pending Verification</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box {{ $stats['performance_percentage'] >= 100 ? 'bg-success' : 'bg-danger' }}">
                            <div class="inner">
                                <h3>{{ number_format($stats['performance_percentage'], 1) }}%</h3>
                                <p>Target Achievement</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Progress -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Performance</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Target Amount:</strong> {{ number_format($stats['target_amount'], 2) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Achieved:</strong> {{ number_format($stats['total_amount'], 2) }}
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 30px;">
                            <div class="progress-bar {{ $stats['performance_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}" 
                                 style="width: {{ min($stats['performance_percentage'], 100) }}%">
                                {{ number_format($stats['performance_percentage'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs for Collections and Reports -->
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#collections" data-toggle="tab">Recent Collections</a></li>
                            <li class="nav-item"><a class="nav-link" href="#reports" data-toggle="tab">Daily Reports</a></li>
                            <li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab">Details</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Collections Tab -->
                            <div class="active tab-pane" id="collections">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Receipt</th>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentCollections as $collection)
                                            <tr>
                                                <td><a href="{{ url('field-agent/collection/' . $collection->id . '/show') }}">{{ $collection->receipt_number }}</a></td>
                                                <td>{{ $collection->collection_date->format('Y-m-d') }}</td>
                                                <td>{{ $collection->client->first_name ?? 'N/A' }} {{ $collection->client->last_name ?? '' }}</td>
                                                <td>{{ $collection->collection_type_label }}</td>
                                                <td>{{ number_format($collection->amount, 2) }}</td>
                                                <td>{!! $collection->status_badge !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No collections yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Reports Tab -->
                            <div class="tab-pane" id="reports">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Collections</th>
                                            <th>Amount</th>
                                            <th>Deposited</th>
                                            <th>Variance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentReports as $report)
                                            <tr>
                                                <td><a href="{{ url('field-agent/daily-report/' . $report->id . '/show') }}">{{ $report->report_date->format('Y-m-d') }}</a></td>
                                                <td>{{ $report->total_collections }}</td>
                                                <td>{{ number_format($report->total_amount_collected, 2) }}</td>
                                                <td>{{ number_format($report->cash_deposited_to_branch, 2) }}</td>
                                                <td>
                                                    <span class="badge {{ $report->variance == 0 ? 'badge-success' : 'badge-danger' }}">
                                                        {{ number_format($report->variance, 2) }}
                                                    </span>
                                                </td>
                                                <td>{!! $report->status_badge !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No reports yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Details Tab -->
                            <div class="tab-pane" id="details">
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 200px;">Agent Code:</th>
                                        <td>{{ $agent->agent_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Name:</th>
                                        <td>{{ $agent->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $agent->user->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Branch:</th>
                                        <td>{{ $agent->branch->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number:</th>
                                        <td>{{ $agent->phone_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>National ID:</th>
                                        <td>{{ $agent->national_id ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Commission Rate:</th>
                                        <td>{{ $agent->commission_rate }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Monthly Target:</th>
                                        <td>{{ number_format($agent->target_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($agent->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($agent->status == 'suspended')
                                                <span class="badge badge-warning">Suspended</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($agent->notes)
                                        <tr>
                                            <th>Notes:</th>
                                            <td>{{ $agent->notes }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $agent->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
