@extends('core::layouts.master')
@section('title')
    Edit Group Loan Member Allocations
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Member Allocations</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/show')}}">Loan #{{$loan->id}}</a></li>
                        <li class="breadcrumb-item active">Edit Allocations</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        <form method="post" action="{{route('loan.member-allocations.update', $loan->id)}}">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="row">
                <div class="col-md-12">
                    <!-- Loan Info Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Loan Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Group:</strong> {{$loan->group->name}}
                                </div>
                                <div class="col-md-4">
                                    <strong>Principal Amount:</strong> {{number_format($loan->principal, 2)}}
                                </div>
                                <div class="col-md-4">
                                    <strong>Allocated Members:</strong> {{$loan->memberAllocations->count()}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocations Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Member Allocations</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="distributeEvenly()">
                                    <i class="fas fa-balance-scale"></i> Distribute Evenly
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="allocations-table">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Role</th>
                                            <th>Allocated Amount</th>
                                            <th>Percentage (%)</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($loan->memberAllocations as $index => $allocation)
                                        <tr>
                                            <td>
                                                {{$allocation->client->first_name}} {{$allocation->client->last_name}}
                                                <input type="hidden" name="allocations[{{$index}}][id]" value="{{$allocation->id}}">
                                                <input type="hidden" name="allocations[{{$index}}][client_id]" value="{{$allocation->client_id}}">
                                            </td>
                                            <td>
                                                @php
                                                    $groupMember = $loan->group->active_members->where('client_id', $allocation->client_id)->first();
                                                @endphp
                                                @if($groupMember)
                                                    <span class="badge badge-info">{{ucfirst($groupMember->role)}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="allocations[{{$index}}][allocated_amount]" 
                                                       class="form-control allocation-amount" 
                                                       step="0.01" 
                                                       min="0" 
                                                       value="{{$allocation->allocated_amount}}"
                                                       data-index="{{$index}}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="allocations[{{$index}}][allocated_percentage]" 
                                                       class="form-control allocation-percentage" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="100" 
                                                       value="{{$allocation->allocated_percentage}}"
                                                       data-index="{{$index}}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="allocations[{{$index}}][notes]" 
                                                       class="form-control" 
                                                       value="{{$allocation->notes}}"
                                                       placeholder="Optional notes">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="2">Total</th>
                                            <th>
                                                <span id="total-amount">0.00</span>
                                                <small class="text-muted d-block">Target: {{number_format($loan->principal, 2)}}</small>
                                            </th>
                                            <th>
                                                <span id="total-percentage">0.00</span>%
                                                <small class="text-muted d-block">Target: 100%</small>
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
                                <i class="fas fa-save"></i> Update Allocations
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
    const memberCount = {{$loan->memberAllocations->count()}};
    
    $(document).ready(function() {
        // Update totals when amounts change
        $('.allocation-amount').on('input', function() {
            const index = $(this).data('index');
            const amount = parseFloat($(this).val()) || 0;
            const percentage = (amount / loanPrincipal) * 100;
            
            $(`input[name="allocations[${index}][allocated_percentage]"]`).val(percentage.toFixed(2));
            updateTotals();
        });
        
        // Update amounts when percentages change
        $('.allocation-percentage').on('input', function() {
            const index = $(this).data('index');
            const percentage = parseFloat($(this).val()) || 0;
            const amount = (percentage / 100) * loanPrincipal;
            
            $(`input[name="allocations[${index}][allocated_amount]"]`).val(amount.toFixed(2));
            updateTotals();
        });
        
        // Initial calculation
        updateTotals();
    });
    
    function distributeEvenly() {
        const amountPerMember = loanPrincipal / memberCount;
        const percentagePerMember = 100 / memberCount;
        
        $('.allocation-amount').val(amountPerMember.toFixed(2));
        $('.allocation-percentage').val(percentagePerMember.toFixed(2));
        
        updateTotals();
    }
    
    function updateTotals() {
        let totalAmount = 0;
        let totalPercentage = 0;
        
        $('.allocation-amount').each(function() {
            totalAmount += parseFloat($(this).val()) || 0;
        });
        
        $('.allocation-percentage').each(function() {
            totalPercentage += parseFloat($(this).val()) || 0;
        });
        
        $('#total-amount').text(totalAmount.toFixed(2));
        $('#total-percentage').text(totalPercentage.toFixed(2));
        
        // Validation
        const amountDiff = Math.abs(totalAmount - loanPrincipal);
        const percentageDiff = Math.abs(totalPercentage - 100);
        
        let alerts = '';
        let isValid = true;
        
        if (amountDiff > 0.01) {
            alerts += `<div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Total allocated amount (${totalAmount.toFixed(2)}) does not equal loan principal (${loanPrincipal.toFixed(2)})
            </div>`;
            isValid = false;
        }
        
        if (percentageDiff > 0.01) {
            alerts += `<div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Total percentage (${totalPercentage.toFixed(2)}%) does not equal 100%
            </div>`;
            isValid = false;
        }
        
        if (isValid && totalAmount > 0) {
            alerts = `<div class="alert alert-success">
                <i class="fas fa-check"></i>
                Allocations are valid and ready to submit
            </div>`;
        }
        
        $('#validation-alerts').html(alerts);
        $('#submit-btn').prop('disabled', !isValid || totalAmount === 0);
    }
</script>
@endsection
