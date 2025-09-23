@extends('core::layouts.master')
@section('title')
    Member Allocation Details
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h6 class="box-title">
                Member Allocation Details
                <nav aria-label="breadcrumb" style="display: inline-block; margin-left: 20px;">
                    <ol class="breadcrumb" style="background: none; margin: 0; padding: 0;">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/show')}}">Loan #{{$loan->id}}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/member-allocations')}}">Member Allocations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Allocation Details</li>
                    </ol>
                </nav>
            </h6>

            <div class="box-tools pull-right">
                <a href="{{url('loan/'.$loan->id.'/member-allocations')}}" class="btn btn-info btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <!-- Allocation Information -->
                <div class="col-md-8">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h4 class="box-title">Allocation Information</h4>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-condensed">
                                        <tr>
                                            <td><strong>Member Name:</strong></td>
                                            <td>{{$allocation->client->first_name}} {{$allocation->client->last_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Group:</strong></td>
                                            <td><a href="{{url('client/group/'.$loan->group->id.'/show')}}">{{$loan->group->name}}</a></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loan:</strong></td>
                                            <td><a href="{{url('loan/'.$loan->id.'/show')}}">Loan #{{$loan->id}}</a></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Allocated Amount:</strong></td>
                                            <td>{{number_format($allocation->allocated_amount, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Allocated Percentage:</strong></td>
                                            <td>{{number_format($allocation->allocated_percentage, 2)}}%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($allocation->status == 'active')
                                                    <span class="label label-success">Active</span>
                                                @elseif($allocation->status == 'completed')
                                                    <span class="label label-info">Completed</span>
                                                @elseif($allocation->status == 'defaulted')
                                                    <span class="label label-danger">Defaulted</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-condensed">
                                        <tr>
                                            <td><strong>Principal Paid:</strong></td>
                                            <td>{{number_format($allocation->principal_paid, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Interest Paid:</strong></td>
                                            <td>{{number_format($allocation->interest_paid, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fees Paid:</strong></td>
                                            <td>{{number_format($allocation->fees_paid, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Penalties Paid:</strong></td>
                                            <td>{{number_format($allocation->penalties_paid, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Paid:</strong></td>
                                            <td><strong>{{number_format($allocation->total_paid, 2)}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Outstanding Balance:</strong></td>
                                            <td>
                                                <strong style="color: {{$allocation->outstanding_balance > 0 ? 'red' : 'green'}}">
                                                    {{number_format($allocation->outstanding_balance, 2)}}
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Progress -->
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h4 class="box-title">Payment Progress</h4>
                        </div>
                        <div class="box-body text-center">
                            @php
                                $paymentPercentage = $allocation->allocated_amount > 0 ? ($allocation->total_paid / $allocation->allocated_amount) * 100 : 0;
                            @endphp
                            <div class="progress-group">
                                <div class="progress-text">{{number_format($paymentPercentage, 1)}}% Complete</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-{{$paymentPercentage >= 100 ? 'success' : ($paymentPercentage >= 50 ? 'warning' : 'danger')}}" 
                                         style="width: {{min($paymentPercentage, 100)}}%"></div>
                                </div>
                            </div>
                            
                            <div class="info-box bg-{{$allocation->outstanding_balance > 0 ? 'red' : 'green'}}">
                                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Remaining</span>
                                    <span class="info-box-number">{{number_format($allocation->outstanding_balance, 2)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="box box-solid">
                        <div class="box-header">
                            <h4 class="box-title">Quick Actions</h4>
                        </div>
                        <div class="box-body">
                            @if($allocation->outstanding_balance > 0)
                                <a href="{{url('loan/'.$loan->id.'/member-allocations/'.$allocation->id.'/payment/create')}}" class="btn btn-primary btn-block">
                                    <i class="fa fa-plus"></i> Record Payment
                                </a>
                            @endif
                            <a href="{{url('loan/'.$loan->id.'/member-allocations')}}" class="btn btn-default btn-block">
                                <i class="fa fa-list"></i> All Allocations
                            </a>
                            <a href="{{url('loan/'.$loan->id.'/show')}}" class="btn btn-info btn-block">
                                <i class="fa fa-eye"></i> View Loan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($allocation->paymentSchedules && $allocation->paymentSchedules->count() > 0)
            <!-- Payment Schedule -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h4 class="box-title">Payment Schedule</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Due Date</th>
                                            <th>Principal Due</th>
                                            <th>Interest Due</th>
                                            <th>Fees Due</th>
                                            <th>Penalties Due</th>
                                            <th>Total Due</th>
                                            <th>Outstanding</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allocation->paymentSchedules as $schedule)
                                            <tr class="{{$schedule->status == 'paid' ? 'success' : ($schedule->due_date < date('Y-m-d') ? 'danger' : '')}}">
                                                <td>{{$schedule->installment_number}}</td>
                                                <td>{{date('M d, Y', strtotime($schedule->due_date))}}</td>
                                                <td>{{number_format($schedule->principal_due, 2)}}</td>
                                                <td>{{number_format($schedule->interest_due, 2)}}</td>
                                                <td>{{number_format($schedule->fees_due, 2)}}</td>
                                                <td>{{number_format($schedule->penalties_due, 2)}}</td>
                                                <td><strong>{{number_format($schedule->total_due, 2)}}</strong></td>
                                                <td>{{number_format($schedule->outstanding_balance, 2)}}</td>
                                                <td>
                                                    @if($schedule->status == 'paid')
                                                        <span class="label label-success">Paid</span>
                                                    @elseif($schedule->due_date < date('Y-m-d'))
                                                        <span class="label label-danger">Overdue</span>
                                                    @else
                                                        <span class="label label-warning">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="info">
                                            <th colspan="2">Total</th>
                                            <th>{{number_format($allocation->paymentSchedules->sum('principal_due'), 2)}}</th>
                                            <th>{{number_format($allocation->paymentSchedules->sum('interest_due'), 2)}}</th>
                                            <th>{{number_format($allocation->paymentSchedules->sum('fees_due'), 2)}}</th>
                                            <th>{{number_format($allocation->paymentSchedules->sum('penalties_due'), 2)}}</th>
                                            <th>{{number_format($allocation->paymentSchedules->sum('total_due'), 2)}}</th>
                                            <th>{{number_format($allocation->paymentSchedules->sum('outstanding_balance'), 2)}}</th>
                                            <th>-</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Loan Details -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Loan Details</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Loan Term:</strong> {{$loan->loan_term}} {{ucfirst($loan->repayment_frequency_type)}}</p>
                                <p><strong>Payment Frequency:</strong> Every {{$loan->repayment_frequency}} {{ucfirst($loan->repayment_frequency_type)}}(s)</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Interest Rate:</strong> {{$loan->interest_rate}}% {{ucfirst($loan->loan_product->interest_rate_type)}}ly</p>
                                <p><strong>Total Loan Amount:</strong> {{number_format($loan->approved_amount, 2)}}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Disbursed Date:</strong> {{$loan->disbursed_on_date ? date('M d, Y', strtotime($loan->disbursed_on_date)) : 'Not disbursed'}}</p>
                                <p><strong>First Payment Date:</strong> {{$loan->first_payment_date ? date('M d, Y', strtotime($loan->first_payment_date)) : 'Not set'}}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Expected Maturity:</strong> {{$loan->expected_maturity_date ? date('M d, Y', strtotime($loan->expected_maturity_date)) : 'Not set'}}</p>
                                <p><strong>Loan Status:</strong> 
                                    @if($loan->status=='submitted')
                                        <span class="label label-warning">Pending Approval</span>
                                    @elseif($loan->status=='approved')
                                        <span class="label label-warning">Awaiting Disbursement</span>
                                    @elseif($loan->status=='active')
                                        <span class="label label-info">Active</span>
                                    @elseif($loan->status=='closed')
                                        <span class="label label-success">Closed</span>
                                    @else
                                        <span class="label label-default">{{ucfirst($loan->status)}}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
