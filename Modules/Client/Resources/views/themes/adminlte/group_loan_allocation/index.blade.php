@extends('core::layouts.master')
@section('title')
    Group Loan Member Allocations
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Group Loan Member Allocations
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan')}}">{{ trans_choice('loan::general.loan',2) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/show')}}">Loan #{{$loan->id}}</a></li>
                        <li class="breadcrumb-item active">Member Allocations</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-12">
                <!-- Loan Summary Card -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Loan Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon"><i class="fas fa-hashtag"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Loan ID</span>
                                        <span class="info-box-number">#{{$loan->id}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Group</span>
                                        <span class="info-box-number" style="font-size: 14px;"><a href="{{url('client/group/'.$loan->group_id.'/show')}}">{{$loan->group->name}}</a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Principal</span>
                                        <span class="info-box-number">{{number_format($loan->principal, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-info"><i class="fas fa-percent"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Interest</span>
                                        @php
                                            $totalAllocatedInterest = $loan->memberAllocations->sum('allocated_interest');
                                        @endphp
                                        <span class="info-box-number">{{number_format($totalAllocatedInterest, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-{{$loan->status == 'active' ? 'success' : 'secondary'}}"><i class="fas fa-flag"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Status</span>
                                        <span class="info-box-number" style="font-size: 16px;"><span class="badge badge-{{$loan->status == 'active' ? 'success' : 'secondary'}}">{{ucfirst($loan->status)}}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Member Allocations Card -->
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calculator"></i> Member Allocations</h3>
                        <div class="card-tools">
                            @if(!$loan->memberAllocations()->exists())
                                @can('client.groups.manage_members')
                                    <a href="{{url('client/loan/'.$loan->id.'/member-allocations/create')}}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Create Allocations
                                    </a>
                                @endcan
                            @else
                                @if($loan->status == 'approved')
                                    @can('loan.loans.disburse_loan')
                                        <a href="#" data-toggle="modal" data-target="#disburse_group_loan_modal" class="btn btn-success btn-sm mr-2">
                                            <i class="fas fa-hand-holding-usd"></i> Disburse Loan
                                        </a>
                                    @endcan
                                @endif
                                @can('client.groups.manage_members')
                                    @if($loan->status !== 'active' && $loan->status !== 'closed' && $loan->status !== 'withdrawn' && $loan->status !== 'written_off')
                                        <a href="{{url('client/loan/'.$loan->id.'/member-allocations/edit')}}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit Allocations
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Cannot edit allocations after loan is disbursed">
                                            <i class="fas fa-lock"></i> Edit Allocations (Locked)
                                        </button>
                                    @endif
                                @endcan
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($loan->memberAllocations()->exists())
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="allocations-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Member</th>
                                            <th>Principal</th>
                                            <th>Interest</th>
                                            <th>Total (P+I)</th>
                                            <th>%</th>
                                            <th>Paid</th>
                                            <th>Outstanding</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($loan->memberAllocations as $allocation)
                                        <tr>
                                            <td><strong>{{$allocation->client->first_name}} {{$allocation->client->last_name}}</strong></td>
                                            <td>{{number_format($allocation->allocated_amount, 2)}}</td>
                                            <td class="text-info">{{number_format($allocation->allocated_interest ?? 0, 2)}}</td>
                                            <td class="text-success"><strong>{{number_format(($allocation->allocated_amount ?? 0) + ($allocation->allocated_interest ?? 0), 2)}}</strong></td>
                                            <td><span class="badge badge-secondary">{{number_format($allocation->allocated_percentage, 2)}}%</span></td>
                                            <td>{{number_format($allocation->total_paid, 2)}}</td>
                                            <td>{{number_format($allocation->outstanding_balance + ($allocation->interest_outstanding ?? 0), 2)}}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{$allocation->payment_percentage >= 100 ? 'success' : ($allocation->payment_percentage >= 50 ? 'warning' : 'danger')}}" 
                                                         role="progressbar" 
                                                         style="width: {{min($allocation->payment_percentage, 100)}}%" 
                                                         aria-valuenow="{{$allocation->payment_percentage}}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        {{number_format($allocation->payment_percentage, 1)}}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{$allocation->status == 'active' ? 'success' : ($allocation->status == 'completed' ? 'primary' : 'danger')}}">
                                                    {{ucfirst($allocation->status)}}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{url('client/loan/'.$loan->id.'/member-allocations/'.$allocation->id)}}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No member allocations have been created for this group loan yet. 
                                @can('client.groups.manage_members')
                                    <a href="{{url('client/loan/'.$loan->id.'/member-allocations/create')}}">Create allocations</a> to track individual member contributions.
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Summary Card -->
                @if($loan->memberAllocations()->exists())
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-pie"></i> Payment Summary</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $totalPrincipal = $loan->memberAllocations->sum('allocated_amount');
                            $totalInterest = $loan->memberAllocations->sum('allocated_interest');
                            $totalAmount = $totalPrincipal + $totalInterest;
                            $totalPaid = $loan->memberAllocations->sum('total_paid');
                            $principalOutstanding = $loan->memberAllocations->sum('outstanding_balance');
                            $interestOutstanding = $loan->memberAllocations->sum('interest_outstanding');
                            $totalOutstanding = $principalOutstanding + $interestOutstanding;
                            $progress = $totalAmount > 0 ? round(($totalPaid / $totalAmount) * 100, 1) : 0;
                        @endphp
                        <div class="row">
                            <div class="col-md-2">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Principal</span>
                                        <span class="info-box-number">{{number_format($totalPrincipal, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-percent"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Interest</span>
                                        <span class="info-box-number">{{number_format($totalInterest, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-calculator"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total (P+I)</span>
                                        <span class="info-box-number">{{number_format($totalAmount, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Paid</span>
                                        <span class="info-box-number">{{number_format($totalPaid, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Outstanding</span>
                                        <span class="info-box-number">{{number_format($totalOutstanding, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="info-box">
                                    <span class="info-box-icon bg-{{$progress >= 100 ? 'success' : ($progress >= 50 ? 'warning' : 'danger')}}"><i class="fas fa-chart-line"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Progress</span>
                                        <span class="info-box-number">{{$progress}}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    
    <!-- Disburse Group Loan Modal -->
    @if($loan->status == 'approved')
    @can('loan.loans.disburse_loan')
    <div class="modal fade" id="disburse_group_loan_modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title"><i class="fas fa-hand-holding-usd"></i> Disburse Group Loan</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>
                <form method="post" action="{{ url('loan/'.$loan->id.'/disburse_loan') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Loan Information -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> Loan Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Loan ID:</th>
                                                <td><strong>#{{$loan->id}}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Group:</th>
                                                <td><a href="{{url('client/group/'.$loan->group_id.'/show')}}" target="_blank">{{$loan->group->name}}</a></td>
                                            </tr>
                                            <tr>
                                                <th>Product:</th>
                                                <td>{{$loan->loan_product->name}}</td>
                                            </tr>
                                            <tr>
                                                <th>Principal:</th>
                                                <td><strong class="text-primary">{{number_format($loan->principal, 2)}}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Interest:</th>
                                                <td><strong class="text-info">{{number_format($loan->memberAllocations->sum('allocated_interest'), 2)}}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Total Repayment:</th>
                                                <td><strong class="text-success">{{number_format($loan->principal + $loan->memberAllocations->sum('allocated_interest'), 2)}}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Interest Rate:</th>
                                                <td>{{$loan->interest_rate}}% ({{ucfirst($loan->loan_product->interest_rate_type)}}ly)</td>
                                            </tr>
                                            <tr>
                                                <th>Term:</th>
                                                <td>{{$loan->loan_term}} {{ucfirst($loan->repayment_frequency_type)}}(s)</td>
                                            </tr>
                                            <tr>
                                                <th>Repayment:</th>
                                                <td>Every {{$loan->repayment_frequency}} {{ucfirst($loan->repayment_frequency_type)}}(s)</td>
                                            </tr>
                                            <tr>
                                                <th>Approved Date:</th>
                                                <td>{{$loan->approved_on_date}}</td>
                                            </tr>
                                            <tr>
                                                <th>Approved By:</th>
                                                <td>{{$loan->approved_by_user->first_name ?? ''}} {{$loan->approved_by_user->last_name ?? ''}}</td>
                                            </tr>
                                            <tr>
                                                <th>Members:</th>
                                                <td><span class="badge badge-info">{{$loan->memberAllocations->count()}} members</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Member Allocations Summary -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-users"></i> Member Allocations</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Member</th>
                                                <th>Principal</th>
                                                <th>Interest</th>
                                                <th>Total (P+I)</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($loan->memberAllocations as $allocation)
                                            <tr>
                                                <td><strong>{{$allocation->client->first_name}} {{$allocation->client->last_name}}</strong></td>
                                                <td>{{number_format($allocation->allocated_amount, 2)}}</td>
                                                <td class="text-info">{{number_format($allocation->allocated_interest ?? 0, 2)}}</td>
                                                <td class="text-success"><strong>{{number_format(($allocation->allocated_amount ?? 0) + ($allocation->allocated_interest ?? 0), 2)}}</strong></td>
                                                <td><span class="badge badge-secondary">{{number_format($allocation->allocated_percentage, 2)}}%</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="font-weight-bold">
                                            <tr class="table-active">
                                                <td><strong>Total</strong></td>
                                                <td><strong>{{number_format($loan->memberAllocations->sum('allocated_amount'), 2)}}</strong></td>
                                                <td class="text-info"><strong>{{number_format($loan->memberAllocations->sum('allocated_interest'), 2)}}</strong></td>
                                                <td class="text-success"><strong>{{number_format($loan->memberAllocations->sum('allocated_amount') + $loan->memberAllocations->sum('allocated_interest'), 2)}}</strong></td>
                                                <td><span class="badge badge-secondary">100.00%</span></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Disbursement Details -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-calendar-check"></i> Disbursement Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="disbursed_on_date">Disbursement Date <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   name="disbursed_on_date" 
                                                   id="disbursed_on_date" 
                                                   class="form-control" 
                                                   value="{{$loan->expected_disbursement_date}}" 
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_payment_date">First Payment Date <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   name="first_payment_date" 
                                                   id="first_payment_date" 
                                                   class="form-control" 
                                                   value="{{$loan->expected_first_payment_date}}" 
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="payment_type_id" value="1">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Note:</strong> Loan disbursement will be automatically deposited to the group's account.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-hand-holding-usd"></i> Disburse Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
    @endif
@endsection

@section('scripts')
    @if($loan->memberAllocations()->exists())
    <script>
        $(document).ready(function() {
            $('#allocations-table').DataTable({
                processing: false,
                serverSide: false,
                order: [[0, 'asc']],
                pageLength: 25
            });
        });
    </script>
    @endif
@endsection
