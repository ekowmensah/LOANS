@extends('core::layouts.master')

@section('title')
    {{ trans_choice('fieldagent::general.field_agent', 1) }} {{ trans_choice('core::general.dashboard', 1) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('fieldagent::general.field_agent', 1) }} {{ trans_choice('core::general.dashboard', 1) }}
                        <small>Welcome, {{ $fieldAgent->user->first_name }}!</small>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ trans_choice('core::general.home', 1) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.dashboard', 1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            <!-- Daily Report Alert -->
            @if(!$todayReport)
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Daily Report Pending!</h5>
                You haven't submitted your daily report yet. 
                <a href="{{ url('field-agent/daily-report/create') }}" class="btn btn-sm btn-warning ml-2">
                    <i class="fas fa-file-alt"></i> Submit Now
                </a>
            </div>
            @else
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Daily Report Submitted!</h5>
                Your daily report for today has been submitted.
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row">
                <!-- Today's Collections -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($todayAmount, 2) }}</h3>
                            <p>Today's Collections ({{ $todayCount }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="{{ url('field-agent/collection') }}" class="small-box-footer">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Week's Collections -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($weekAmount, 2) }}</h3>
                            <p>This Week ({{ $weekCount }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <a href="{{ url('field-agent/collection') }}" class="small-box-footer">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Month's Collections -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($monthAmount, 2) }}</h3>
                            <p>This Month ({{ $monthCount }})</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="{{ url('field-agent/collection') }}" class="small-box-footer">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Pending Verifications -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $pendingVerifications }}</h3>
                            <p>Pending Verifications</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ url('field-agent/collection') }}" class="small-box-footer">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Assigned Clients</span>
                            <span class="info-box-number">{{ $assignedClients }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Loans</span>
                            <span class="info-box-number">{{ $activeLoans }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Loans Due/Overdue</span>
                            <span class="info-box-number">{{ $dueLoans->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <a href="{{ url('field-agent/collection/create') }}" class="btn btn-app btn-primary btn-block">
                                        <i class="fas fa-plus-circle"></i> Record Collection
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ url('field-agent/daily-report/create') }}" class="btn btn-app btn-success btn-block">
                                        <i class="fas fa-file-alt"></i> Submit Report
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ url('field-agent/collection') }}" class="btn btn-app btn-info btn-block">
                                        <i class="fas fa-list"></i> View Collections
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ url('client') }}" class="btn btn-app btn-warning btn-block">
                                        <i class="fas fa-users"></i> My Clients
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Due/Overdue Loans -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title"><i class="fas fa-exclamation-triangle text-warning"></i> Loans Due/Overdue</h3>
                            <div class="card-tools">
                                <span class="badge badge-warning">{{ $dueLoans->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($dueLoans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Loan ID</th>
                                            <th>Due Date</th>
                                            <th>Amount Due</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dueLoans as $loan)
                                        @php
                                            $schedule = $loan->repayment_schedules->first();
                                            $amountDue = 0;
                                            if($schedule) {
                                                $amountDue = ($schedule->principal - $schedule->principal_repaid_derived) + 
                                                            ($schedule->interest - $schedule->interest_repaid_derived);
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $loan->client->first_name }} {{ $loan->client->last_name }}</td>
                                            <td><a href="{{ url('loan/'.$loan->id.'/show') }}">#{{ $loan->id }}</a></td>
                                            <td>
                                                @if($schedule)
                                                <span class="badge badge-{{ $schedule->due_date < today() ? 'danger' : 'warning' }}">
                                                    {{ $schedule->due_date }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($amountDue, 2) }}</td>
                                            <td>
                                                <a href="{{ url('field-agent/collection/create?client_id='.$loan->client_id.'&loan_id='.$loan->id) }}" 
                                                   class="btn btn-xs btn-success">
                                                    <i class="fas fa-money-bill"></i> Collect
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-check-circle fa-3x mb-2"></i>
                                <p>No loans due or overdue!</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Collections -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title"><i class="fas fa-history"></i> Recent Collections</h3>
                            <div class="card-tools">
                                <a href="{{ url('field-agent/collection') }}" class="btn btn-tool btn-sm">
                                    <i class="fas fa-eye"></i> View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($recentCollections->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentCollections as $collection)
                                        <tr>
                                            <td>{{ $collection->collection_date }}</td>
                                            <td>{{ $collection->client->first_name ?? 'N/A' }}</td>
                                            <td>{{ number_format($collection->amount, 2) }}</td>
                                            <td>
                                                @if($collection->status == 'verified')
                                                <span class="badge badge-success">Verified</span>
                                                @elseif($collection->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @else
                                                <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-2"></i>
                                <p>No collections yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Auto-refresh every 5 minutes
        setTimeout(function(){
            location.reload();
        }, 300000);
    </script>
@endsection
