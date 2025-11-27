@extends('core::layouts.master')

@section('title')
    {{ trans_choice('fieldagent::general.daily_report', 1) }} - {{ $report->report_date->format('Y-m-d') }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('fieldagent::general.daily_report', 1) }} Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/daily-report') }}">{{ trans_choice('fieldagent::general.daily_report', 2) }}</a></li>
                        <li class="breadcrumb-item active">{{ $report->report_date->format('Y-m-d') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $report->total_collections }}</h3>
                        <p>Total Collections</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($report->total_amount_collected, 0) }}</h3>
                        <p>Amount Collected</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $report->total_clients_visited }}</h3>
                        <p>Clients Visited</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box {{ $report->variance == 0 ? 'bg-success' : 'bg-danger' }}">
                    <div class="inner">
                        <h3>{{ number_format($report->variance, 2) }}</h3>
                        <p>Cash Variance</p>
                    </div>
                    <div class="icon">
                        <i class="fas {{ $report->variance == 0 ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Report Information</h3>
                        <div class="card-tools">
                            {!! $report->status_badge !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 200px;">Report Date:</th>
                                <td><strong>{{ $report->report_date->format('Y-m-d') }}</strong></td>
                            </tr>
                            <tr>
                                <th>Field Agent:</th>
                                <td>{{ $report->fieldAgent->agent_code }} - {{ $report->fieldAgent->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Total Collections:</th>
                                <td>{{ $report->total_collections }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount Collected:</th>
                                <td><strong class="text-success">{{ number_format($report->total_amount_collected, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Clients Visited:</th>
                                <td>{{ $report->total_clients_visited }}</td>
                            </tr>
                            <tr>
                                <th>Clients Who Paid:</th>
                                <td>{{ $report->total_clients_paid }}</td>
                            </tr>
                        </table>

                        <hr>
                        <h5>Cash Reconciliation</h5>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 200px;">Opening Cash Balance:</th>
                                <td>{{ number_format($report->opening_cash_balance, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Closing Cash Balance:</th>
                                <td>{{ number_format($report->closing_cash_balance, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Cash Deposited to Branch:</th>
                                <td>{{ number_format($report->cash_deposited_to_branch, 2) }}</td>
                            </tr>
                            @if($report->deposit_receipt_number)
                                <tr>
                                    <th>Deposit Receipt Number:</th>
                                    <td>{{ $report->deposit_receipt_number }}</td>
                                </tr>
                            @endif
                            @if($report->depositedBy)
                                <tr>
                                    <th>Deposited To (Teller):</th>
                                    <td>{{ $report->depositedBy->first_name }} {{ $report->depositedBy->last_name }}</td>
                                </tr>
                            @endif
                        </table>

                        <div class="alert {{ $report->variance == 0 ? 'alert-success' : 'alert-danger' }}">
                            <h5><i class="icon fas {{ $report->variance == 0 ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i> Variance Analysis</h5>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th style="width: 200px;">Expected Cash:</th>
                                    <td>{{ number_format($report->opening_cash_balance + $report->total_amount_collected, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Actual Cash:</th>
                                    <td>{{ number_format($report->closing_cash_balance + $report->cash_deposited_to_branch, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Variance:</th>
                                    <td><strong>{{ number_format($report->variance, 2) }}</strong></td>
                                </tr>
                            </table>
                            @if($report->variance == 0)
                                <p class="mb-0 mt-2"><i class="fas fa-check"></i> Perfect! No variance detected.</p>
                            @elseif($report->variance > 0)
                                <p class="mb-0 mt-2"><i class="fas fa-info-circle"></i> Excess cash of {{ number_format($report->variance, 2) }}</p>
                            @else
                                <p class="mb-0 mt-2"><i class="fas fa-exclamation-circle"></i> Short by {{ number_format(abs($report->variance), 2) }}</p>
                            @endif
                        </div>

                        @if($report->notes)
                            <hr>
                            <h5>Notes</h5>
                            <p>{{ $report->notes }}</p>
                        @endif

                        @if($report->status == 'submitted' || $report->status == 'approved')
                            <hr>
                            <h5>Submission Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 200px;">Submitted At:</th>
                                    <td>{{ $report->submitted_at ? $report->submitted_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                @if($report->status == 'approved')
                                    <tr>
                                        <th>Approved By:</th>
                                        <td>{{ $report->approvedBy->first_name ?? 'N/A' }} {{ $report->approvedBy->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Approved At:</th>
                                        <td>{{ $report->approved_at ? $report->approved_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                @endif
                            </table>
                        @endif

                        @if($report->status == 'rejected')
                            <hr>
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> Rejection Details</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 200px;">Rejected By:</th>
                                        <td>{{ $report->approvedBy->first_name ?? 'N/A' }} {{ $report->approvedBy->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Rejected At:</th>
                                        <td>{{ $report->approved_at ? $report->approved_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reason:</th>
                                        <td>{{ $report->rejection_reason }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if($report->canBeSubmitted())
                            <form method="post" action="{{ url('field-agent/daily-report/' . $report->id . '/submit') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Submit this report for approval?')">
                                    <i class="fas fa-paper-plane"></i> Submit for Approval
                                </button>
                            </form>
                        @endif

                        @if($report->canBeApproved() && auth()->user()->can('field_agent.reports.approve'))
                            <a href="{{ url('field-agent/daily-report/' . $report->id . '/approve') }}" class="btn btn-success" onclick="return confirm('Approve this report?')">
                                <i class="fas fa-check"></i> Approve Report
                            </a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> Reject Report
                            </button>
                        @endif

                        <a href="{{ url('field-agent/daily-report') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Collections for this Report -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Collections for {{ $report->report_date->format('Y-m-d') }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Receipt</th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $collection)
                                    <tr>
                                        <td><a href="{{ url('field-agent/collection/' . $collection->id . '/show') }}">{{ $collection->receipt_number }}</a></td>
                                        <td>{{ $collection->client->first_name ?? 'N/A' }} {{ $collection->client->last_name ?? '' }}</td>
                                        <td>{{ $collection->collection_type_label }}</td>
                                        <td>{{ number_format($collection->amount, 2) }}</td>
                                        <td>{{ $collection->collection_time ? $collection->collection_time->format('H:i') : 'N/A' }}</td>
                                        <td>{!! $collection->status_badge !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No collections for this date</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($collections->isNotEmpty())
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="3">Total:</td>
                                        <td>{{ number_format($collections->sum('amount'), 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Agent Info -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if($report->fieldAgent->photo)
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset($report->fieldAgent->photo) }}" alt="Agent photo">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('assets/dist/img/avatar.png') }}" alt="Agent photo">
                            @endif
                        </div>

                        <h3 class="profile-username text-center">{{ $report->fieldAgent->full_name }}</h3>
                        <p class="text-muted text-center">{{ $report->fieldAgent->agent_code }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Branch</b>
                                <a class="float-right">{{ $report->fieldAgent->branch->name ?? 'N/A' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Phone</b>
                                <a class="float-right">{{ $report->fieldAgent->phone_number ?? 'N/A' }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Stats</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-percentage mr-1"></i> Collection Rate</strong>
                        <p class="text-muted">
                            {{ $report->total_clients_visited > 0 ? number_format(($report->total_clients_paid / $report->total_clients_visited) * 100, 1) : 0 }}% 
                            ({{ $report->total_clients_paid }}/{{ $report->total_clients_visited }})
                        </p>
                        <hr>

                        <strong><i class="fas fa-money-bill-wave mr-1"></i> Average Collection</strong>
                        <p class="text-muted">
                            {{ $report->total_collections > 0 ? number_format($report->total_amount_collected / $report->total_collections, 2) : '0.00' }}
                        </p>
                        <hr>

                        <strong><i class="fas fa-calendar mr-1"></i> Report Date</strong>
                        <p class="text-muted">{{ $report->report_date->format('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reject Modal -->
    @if($report->canBeApproved())
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ url('field-agent/daily-report/' . $report->id . '/reject') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Daily Report</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="rejection_reason">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
