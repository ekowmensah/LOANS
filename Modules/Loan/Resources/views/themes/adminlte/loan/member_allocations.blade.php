@extends('core::layouts.master')
@section('title')
    {{trans_choice('loan::general.loan',1)}} {{trans_choice('general.detail',2)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h6 class="box-title">
                {{trans_choice('loan::general.loan',1)}} #{{$loan->id}} - Member Allocations
                @if($loan->status=='submitted')
                    <span class="label label-warning">{{trans_choice('loan::general.pending_approval',1)}}</span>
                @endif
                @if($loan->status=='approved')
                    <span class="label label-warning">{{trans_choice('loan::general.awaiting_disbursement',1)}}</span>
                @endif
                @if($loan->status=='active')
                    <span class="label label-info">{{trans_choice('loan::general.active',1)}}</span>
                @endif
                @if($loan->status=='withdrawn')
                    <span class="label label-danger">{{trans_choice('loan::general.withdrawn',1)}}</span>
                @endif
                @if($loan->status=='rejected')
                    <span class="label label-danger">{{trans_choice('loan::general.rejected',1)}}</span>
                @endif
                @if($loan->status=='closed')
                    <span class="label label-success">{{trans_choice('loan::general.closed',1)}}</span>
                @endif
                @if($loan->status=='written_off')
                    <span class="label label-danger">{{trans_choice('loan::general.written_off',1)}}</span>
                @endif
                @if($loan->status=='rescheduled')
                    <span class="label label-info">{{trans_choice('loan::general.rescheduled',1)}}</span>
                @endif
                @if($loan->status=='overpaid')
                    <span class="label label-info">{{trans_choice('loan::general.overpaid',1)}}</span>
                @endif
            </h6>

            <div class="box-tools pull-right">
                <a href="{{url('loan/'.$loan->id.'/show')}}" class="btn btn-info btn-sm">
                    {{trans_choice('general.back',1)}}
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>Group: {{$loan->group->name}}</h4>
                    <p>Total Loan Amount: <strong>{{number_format($loan->approved_amount,2)}}</strong></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Allocated Amount</th>
                                    <th>Percentage</th>
                                    <th>Expected Payment</th>
                                    <th>Outstanding Balance</th>
                                    <th>Total Paid</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($loan->memberAllocations && $loan->memberAllocations->count() > 0)
                                    @foreach($loan->memberAllocations as $allocation)
                                        <tr>
                                            <td>
                                                <a href="{{url('loan/'.$loan->id.'/member-allocations/'.$allocation->id)}}">
                                                    {{$allocation->client->first_name}} {{$allocation->client->last_name}}
                                                </a>
                                            </td>
                                            <td>{{number_format($allocation->allocated_amount, 2)}}</td>
                                            <td>
                                                {{number_format($allocation->allocated_percentage, 2)}}%
                                            </td>
                                            <td>
                                                @php
                                                    $interestRate = $loan->interest_rate / 100;
                                                    $loanTerm = $loan->loan_term;
                                                    $repaymentFrequency = $loan->repayment_frequency;
                                                    $interestRateType = $loan->loan_product->interest_rate_type;
                                                    $repaymentFrequencyType = $loan->repayment_frequency_type;
                                                    
                                                    // Calculate number of payments per year
                                                    $paymentsPerYear = 1;
                                                    if ($repaymentFrequencyType === 'days') {
                                                        $paymentsPerYear = 365 / $repaymentFrequency;
                                                    } elseif ($repaymentFrequencyType === 'weeks') {
                                                        $paymentsPerYear = 52 / $repaymentFrequency;
                                                    } elseif ($repaymentFrequencyType === 'months') {
                                                        $paymentsPerYear = 12 / $repaymentFrequency;
                                                    }
                                                    
                                                    // Convert interest rate to payment frequency
                                                    $periodInterestRate = 0;
                                                    if ($interestRateType === 'year') {
                                                        $periodInterestRate = $interestRate / $paymentsPerYear;
                                                    } elseif ($interestRateType === 'month') {
                                                        $periodInterestRate = $interestRate / (12 / $repaymentFrequency);
                                                    }
                                                    
                                                    $totalPayments = $loanTerm / $repaymentFrequency;
                                                    
                                                    // Calculate payment using amortization formula
                                                    $memberPayment = 0;
                                                    if ($periodInterestRate > 0) {
                                                        $memberPayment = $allocation->allocated_amount * ($periodInterestRate * pow(1 + $periodInterestRate, $totalPayments)) / (pow(1 + $periodInterestRate, $totalPayments) - 1);
                                                    } else {
                                                        $memberPayment = $allocation->allocated_amount / $totalPayments;
                                                    }
                                                @endphp
                                                {{number_format($memberPayment, 2)}}
                                            </td>
                                            <td>
                                                {{number_format($allocation->outstanding_balance, 2)}}
                                            </td>
                                            <td>
                                                {{number_format($allocation->total_paid, 2)}}
                                            </td>
                                            <td>
                                                @if($allocation->status == 'active')
                                                    <span class="label label-success">Active</span>
                                                @elseif($allocation->status == 'completed')
                                                    <span class="label label-info">Completed</span>
                                                @elseif($allocation->status == 'defaulted')
                                                    <span class="label label-danger">Defaulted</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{url('loan/'.$loan->id.'/member-allocations/'.$allocation->id)}}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="info">
                                        <th>Total</th>
                                        <th>{{number_format($loan->memberAllocations->sum('allocated_amount'), 2)}}</th>
                                        <th>{{number_format($loan->memberAllocations->sum('allocated_percentage'), 2)}}%</th>
                                        <th>
                                            @php
                                                $totalExpectedPayment = 0;
                                                foreach($loan->memberAllocations as $allocation) {
                                                    $interestRate = $loan->interest_rate / 100;
                                                    $loanTerm = $loan->loan_term;
                                                    $repaymentFrequency = $loan->repayment_frequency;
                                                    $interestRateType = $loan->loan_product->interest_rate_type;
                                                    $repaymentFrequencyType = $loan->repayment_frequency_type;
                                                    
                                                    $paymentsPerYear = 1;
                                                    if ($repaymentFrequencyType === 'days') {
                                                        $paymentsPerYear = 365 / $repaymentFrequency;
                                                    } elseif ($repaymentFrequencyType === 'weeks') {
                                                        $paymentsPerYear = 52 / $repaymentFrequency;
                                                    } elseif ($repaymentFrequencyType === 'months') {
                                                        $paymentsPerYear = 12 / $repaymentFrequency;
                                                    }
                                                    
                                                    $periodInterestRate = 0;
                                                    if ($interestRateType === 'year') {
                                                        $periodInterestRate = $interestRate / $paymentsPerYear;
                                                    } elseif ($interestRateType === 'month') {
                                                        $periodInterestRate = $interestRate / (12 / $repaymentFrequency);
                                                    }
                                                    
                                                    $totalPayments = $loanTerm / $repaymentFrequency;
                                                    
                                                    $memberPayment = 0;
                                                    if ($periodInterestRate > 0) {
                                                        $memberPayment = $allocation->allocated_amount * ($periodInterestRate * pow(1 + $periodInterestRate, $totalPayments)) / (pow(1 + $periodInterestRate, $totalPayments) - 1);
                                                    } else {
                                                        $memberPayment = $allocation->allocated_amount / $totalPayments;
                                                    }
                                                    $totalExpectedPayment += $memberPayment;
                                                }
                                            @endphp
                                            {{number_format($totalExpectedPayment, 2)}}
                                        </th>
                                        <th>{{number_format($loan->memberAllocations->sum('outstanding_balance'), 2)}}</th>
                                        <th>{{number_format($loan->memberAllocations->sum('total_paid'), 2)}}</th>
                                        <th>-</th>
                                        <th>-</th>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No member allocations found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            @if($loan->memberAllocations && $loan->memberAllocations->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Loan Details</h4>
                        <p><strong>Loan Term:</strong> {{$loan->loan_term}} {{ucfirst($loan->repayment_frequency_type)}}</p>
                        <p><strong>Payment Frequency:</strong> Every {{$loan->repayment_frequency}} {{ucfirst($loan->repayment_frequency_type)}}(s)</p>
                        <p><strong>Interest Rate:</strong> {{$loan->interest_rate}}% {{ucfirst($loan->loan_product->interest_rate_type)}}ly</p>
                        <p><strong>Total Number of Payments:</strong> {{$loan->loan_term / $loan->repayment_frequency}}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
