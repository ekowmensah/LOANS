@extends('core::layouts.master')
@section('title')
    Create Group Loan Member Allocations
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Member Allocations</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/show')}}">Loan #{{$loan->id}}</a></li>
                        <li class="breadcrumb-item active">Create Allocations</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        <form method="post" action="{{route('loan.member-allocations.store', $loan->id)}}">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-12">
                    <!-- Loan Info Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Loan Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Group</span>
                                            <span class="info-box-number">{{$loan->group->name}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Principal</span>
                                            <span class="info-box-number">{{number_format($loan->principal, 2)}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-info"><i class="fas fa-percent"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Interest Rate</span>
                                            <span class="info-box-number">{{$loan->interest_rate}}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-success"><i class="fas fa-user-friends"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Members</span>
                                            <span class="info-box-number">{{$loan->group->active_members->count()}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocations Card -->
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calculator"></i> Member Allocations</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-primary" onclick="distributeEvenly()">
                                    <i class="fas fa-balance-scale"></i> Distribute Evenly
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="allocations-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="20%">Member</th>
                                            <th width="10%">Role</th>
                                            <th width="15%">Principal</th>
                                            <th width="15%">Interest</th>
                                            <th width="15%">Total (P+I)</th>
                                            <th width="10%">%</th>
                                            <th width="15%">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($loan->group->active_members as $index => $member)
                                        <tr>
                                            <td>
                                                <strong>{{$member->client->first_name}} {{$member->client->last_name}}</strong>
                                                <input type="hidden" name="allocations[{{$index}}][client_id]" value="{{$member->client_id}}">
                                            </td>
                                            <td>
                                                <span class="badge badge-{{$member->role == 'chairperson' ? 'primary' : ($member->role == 'secretary' ? 'info' : 'secondary')}}">{{ucfirst($member->role)}}</span>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="allocations[{{$index}}][allocated_amount]" 
                                                       class="form-control allocation-amount" 
                                                       step="0.01" 
                                                       min="0" 
                                                       data-index="{{$index}}"
                                                       placeholder="0.00"
                                                       required>
                                            </td>
                                            <td>
                                                <span class="member-interest text-info" data-index="{{$index}}">0.00</span>
                                            </td>
                                            <td>
                                                <strong><span class="member-total text-success" data-index="{{$index}}">0.00</span></strong>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="allocations[{{$index}}][allocated_percentage]" 
                                                       class="form-control allocation-percentage" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="100" 
                                                       data-index="{{$index}}"
                                                       placeholder="0.00"
                                                       readonly
                                                       required>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="allocations[{{$index}}][notes]" 
                                                       class="form-control form-control-sm" 
                                                       placeholder="Optional">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="2"><strong>TOTAL</strong></th>
                                            <th>
                                                <strong><span id="total-amount">0.00</span></strong>
                                                <br><small class="text-muted">Target: {{number_format($loan->principal, 2)}}</small>
                                            </th>
                                            <th>
                                                <strong><span id="total-interest" class="text-info">0.00</span></strong>
                                            </th>
                                            <th>
                                                <strong><span id="total-principal-interest" class="text-success">0.00</span></strong>
                                            </th>
                                            <th>
                                                <strong><span id="total-percentage">0.00</span>%</strong>
                                                <br><small class="text-muted">Target: 100%</small>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div id="validation-alerts"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save"></i> Create Allocations
                            </button>
                            <a href="{{route('loan.member-allocations.index', $loan->id)}}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('scripts')
<script>
    const loanPrincipal = {{$loan->principal}};
    const loanInterestRate = {{$loan->interest_rate}} / 100;
    const loanTerm = {{$loan->loan_term}};
    const repaymentFrequency = {{$loan->repayment_frequency}};
    const interestRateType = '{{$loan->loan_product->interest_rate_type}}';
    const repaymentFrequencyType = '{{$loan->repayment_frequency_type}}';
    const memberCount = {{$loan->group->active_members->count()}};
    
    // Calculate total loan interest (Simple Interest based on term)
    function calculateTotalLoanInterest() {
        if (loanPrincipal <= 0) return 0;
        
        let totalInterest = 0;
        
        // Calculate loan term in years or months based on interest rate type
        if (interestRateType === 'year') {
            // Annual interest rate: Interest = Principal × Rate × Time (in years)
            let termInYears = 0;
            if (repaymentFrequencyType === 'days') {
                termInYears = loanTerm / 365;
            } else if (repaymentFrequencyType === 'weeks') {
                termInYears = loanTerm / 52;
            } else if (repaymentFrequencyType === 'months') {
                termInYears = loanTerm / 12;
            } else if (repaymentFrequencyType === 'years') {
                termInYears = loanTerm;
            }
            totalInterest = loanPrincipal * loanInterestRate * termInYears;
        } else if (interestRateType === 'month') {
            // Monthly interest rate: Interest = Principal × Rate × Term (in months)
            let termInMonths = 0;
            if (repaymentFrequencyType === 'days') {
                termInMonths = loanTerm / 30;
            } else if (repaymentFrequencyType === 'weeks') {
                termInMonths = loanTerm / 4.33;
            } else if (repaymentFrequencyType === 'months') {
                termInMonths = loanTerm;
            } else if (repaymentFrequencyType === 'years') {
                termInMonths = loanTerm * 12;
            }
            totalInterest = loanPrincipal * loanInterestRate * termInMonths;
        }
        
        return totalInterest;
    }
    
    const totalLoanInterest = calculateTotalLoanInterest();
    
    $(document).ready(function() {
        // Update totals when amounts change
        $('.allocation-amount').on('input', function() {
            const index = $(this).data('index');
            const amount = parseFloat($(this).val()) || 0;
            const percentage = loanPrincipal > 0 ? (amount / loanPrincipal) * 100 : 0;
            
            // Calculate member's share of interest
            const memberInterest = (totalLoanInterest * percentage) / 100;
            const memberTotal = amount + memberInterest;
            
            // Update displays
            $(`input[name="allocations[${index}][allocated_percentage]"]`).val(percentage.toFixed(2));
            $(`.member-interest[data-index="${index}"]`).text(memberInterest.toFixed(2));
            $(`.member-total[data-index="${index}"]`).text(memberTotal.toFixed(2));
            
            updateTotals();
        });
        
        // Initial calculation
        updateTotals();
    });
    
    function distributeEvenly() {
        const amountPerMember = loanPrincipal / memberCount;
        
        $('.allocation-amount').each(function() {
            $(this).val(amountPerMember.toFixed(2)).trigger('input');
        });
    }
    
    function updateTotals() {
        let totalAmount = 0;
        let totalInterest = 0;
        let totalPrincipalInterest = 0;
        let totalPercentage = 0;
        
        $('.allocation-amount').each(function() {
            const amount = parseFloat($(this).val()) || 0;
            totalAmount += amount;
        });
        
        $('.member-interest').each(function() {
            const interest = parseFloat($(this).text()) || 0;
            totalInterest += interest;
        });
        
        $('.member-total').each(function() {
            const total = parseFloat($(this).text()) || 0;
            totalPrincipalInterest += total;
        });
        
        $('.allocation-percentage').each(function() {
            totalPercentage += parseFloat($(this).val()) || 0;
        });
        
        $('#total-amount').text(totalAmount.toFixed(2));
        $('#total-interest').text(totalInterest.toFixed(2));
        $('#total-principal-interest').text(totalPrincipalInterest.toFixed(2));
        $('#total-percentage').text(totalPercentage.toFixed(2));
        
        // Validation
        const amountDiff = Math.abs(totalAmount - loanPrincipal);
        const percentageDiff = Math.abs(totalPercentage - 100);
        
        let alerts = '';
        let isValid = true;
        
        if (amountDiff > 0.01) {
            alerts += `<div class="alert alert-warning alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning!</strong> Total allocated amount (${totalAmount.toFixed(2)}) does not equal loan principal (${loanPrincipal.toFixed(2)})
            </div>`;
            isValid = false;
        }
        
        if (percentageDiff > 0.01) {
            alerts += `<div class="alert alert-warning alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning!</strong> Total percentage (${totalPercentage.toFixed(2)}%) does not equal 100%
            </div>`;
            isValid = false;
        }
        
        if (isValid && totalAmount > 0) {
            alerts = `<div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle"></i>
                <strong>Perfect!</strong> Allocations are valid. Total Interest: ${totalInterest.toFixed(2)} | Total Repayment: ${totalPrincipalInterest.toFixed(2)}
            </div>`;
        }
        
        $('#validation-alerts').html(alerts);
        $('#submit-btn').prop('disabled', !isValid || totalAmount === 0);
    }
</script>
@endsection
