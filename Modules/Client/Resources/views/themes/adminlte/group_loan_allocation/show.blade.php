@extends('core::layouts.master')
@section('title')
    Member Allocation Details
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Member Allocation Details
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$allocation->loan_id.'/show')}}">Loan #{{$allocation->loan_id}}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('client/loan/'.$allocation->loan_id.'/member-allocations')}}">Member Allocations</a></li>
                        <li class="breadcrumb-item active">Allocation Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-8">
                <!-- Allocation Details Card -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Allocation Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Member Name:</th>
                                        <td>{{$allocation->client->first_name}} {{$allocation->client->last_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Group:</th>
                                        <td>
                                            <a href="{{url('client/group/'.$allocation->group_id.'/show')}}">{{$allocation->group->name}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Loan:</th>
                                        <td>
                                            <a href="{{url('loan/'.$allocation->loan_id.'/show')}}">Loan #{{$allocation->loan_id}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Principal Allocated:</th>
                                        <td><strong>{{number_format($allocation->allocated_amount, 2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Interest Allocated:</th>
                                        <td class="text-info"><strong>{{number_format($allocation->allocated_interest ?? 0, 2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Total (P+I):</th>
                                        <td class="text-success"><strong>{{number_format(($allocation->allocated_amount ?? 0) + ($allocation->allocated_interest ?? 0), 2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Allocated Percentage:</th>
                                        <td><span class="badge badge-secondary">{{number_format($allocation->allocated_percentage, 2)}}%</span></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge badge-{{$allocation->status == 'active' ? 'success' : ($allocation->status == 'completed' ? 'primary' : 'danger')}}">
                                                {{ucfirst($allocation->status)}}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Principal Paid:</th>
                                        <td>{{number_format($allocation->principal_paid, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Interest Paid:</th>
                                        <td>{{number_format($allocation->interest_paid, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Interest Outstanding:</th>
                                        <td class="text-info"><strong>{{number_format($allocation->interest_outstanding ?? 0, 2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Fees Paid:</th>
                                        <td>{{number_format($allocation->fees_paid, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Penalties Paid:</th>
                                        <td>{{number_format($allocation->penalties_paid, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Paid:</th>
                                        <td><strong>{{number_format($allocation->total_paid, 2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Principal Outstanding:</th>
                                        <td><strong>{{number_format($allocation->outstanding_balance, 2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Total Outstanding (P+I):</th>
                                        <td><strong class="text-{{($allocation->outstanding_balance + ($allocation->interest_outstanding ?? 0)) > 0 ? 'danger' : 'success'}}">{{number_format($allocation->outstanding_balance + ($allocation->interest_outstanding ?? 0), 2)}}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($allocation->notes)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Notes:</h5>
                                <p class="border p-3">{{$allocation->notes}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Payment Progress Card -->
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line"></i> Payment Progress</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar bg-{{$allocation->payment_percentage >= 100 ? 'success' : ($allocation->payment_percentage >= 50 ? 'warning' : 'danger')}}" 
                                 role="progressbar" 
                                 style="width: {{min($allocation->payment_percentage, 100)}}%" 
                                 aria-valuenow="{{$allocation->payment_percentage}}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <strong>{{number_format($allocation->payment_percentage, 1)}}%</strong>
                            </div>
                        </div>
                        <p class="text-center">
                            <strong>{{number_format($allocation->payment_percentage, 1)}}% Complete</strong>
                        </p>
                        
                        <div class="info-box mb-2">
                            <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Principal Remaining</span>
                                <span class="info-box-number">{{number_format($allocation->outstanding_balance, 2)}}</span>
                            </div>
                        </div>
                        
                        <div class="info-box mb-2">
                            <span class="info-box-icon bg-info"><i class="fas fa-percent"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Interest Remaining</span>
                                <span class="info-box-number">{{number_format($allocation->interest_outstanding ?? 0, 2)}}</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <span class="info-box-icon bg-{{($allocation->outstanding_balance + ($allocation->interest_outstanding ?? 0)) > 0 ? 'danger' : 'success'}}"><i class="fas fa-calculator"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Remaining</span>
                                <span class="info-box-number">{{number_format($allocation->outstanding_balance + ($allocation->interest_outstanding ?? 0), 2)}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        @can('client.groups.manage_members')
                            <button type="button" class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#recordPaymentModal">
                                <i class="fas fa-plus"></i> Record Payment
                            </button>
                        @endcan
                        
                        <a href="{{url('client/loan/'.$allocation->loan_id.'/member-allocations')}}" class="btn btn-secondary btn-block mb-2">
                            <i class="fas fa-list"></i> All Allocations
                        </a>
                        
                        <a href="{{url('loan/'.$allocation->loan_id.'/show')}}" class="btn btn-info btn-block">
                            <i class="fas fa-eye"></i> View Loan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Record Payment Modal -->
    @can('client.groups.manage_members')
    <div class="modal fade" id="recordPaymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Record Payment</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Next Installment Info -->
                    <div id="nextInstallmentInfo" class="alert alert-info" style="display: none;">
                        <h6><strong>Next Installment Due:</strong></h6>
                        <div class="row">
                            <div class="col-md-3">
                                <small><strong>Principal:</strong> <span id="principalDue">0.00</span></small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Interest:</strong> <span id="interestDue">0.00</span></small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Fees:</strong> <span id="feesDue">0.00</span></small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Penalties:</strong> <span id="penaltiesDue">0.00</span></small>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small><strong>Total Due:</strong> <span id="totalDue">0.00</span></small>
                            </div>
                            <div class="col-md-6">
                                <small><strong>Due Date:</strong> <span id="dueDate">-</span></small>
                            </div>
                        </div>
                        <div id="overdueWarning" class="mt-2" style="display: none;">
                            <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> This installment is overdue!</small>
                        </div>
                    </div>

                    <form id="recordPaymentForm" method="POST" action="{{ url('client/loan/' . $allocation->loan_id . '/member-allocations/' . $allocation->id . '/payment') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="payment_amount">Payment Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="0.01" min="0.01" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="payFullAmount">Pay Full Amount</button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Enter the total amount the member is paying</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional payment notes..."></textarea>
                        </div>
                        
                        <!-- Payment Distribution Preview -->
                        <div id="paymentPreview" class="alert alert-light" style="display: none;">
                            <h6><strong>Payment Distribution Preview:</strong></h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <small>Principal: <span id="previewPrincipal">0.00</span></small>
                                </div>
                                <div class="col-md-3">
                                    <small>Interest: <span id="previewInterest">0.00</span></small>
                                </div>
                                <div class="col-md-3">
                                    <small>Fees: <span id="previewFees">0.00</span></small>
                                </div>
                                <div class="col-md-3">
                                    <small>Penalties: <span id="previewPenalties">0.00</span></small>
                                </div>
                            </div>
                            <div id="excessPaymentInfo" class="mt-2" style="display: none;">
                                <small class="text-success"><i class="fas fa-info-circle"></i> Excess payment of <span id="excessAmount">0.00</span> will be applied to future installments</small>
                            </div>
                            <div id="insufficientPaymentInfo" class="mt-2" style="display: none;">
                                <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Insufficient payment - this may result in default status</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection

@section('footer-scripts')
<script>
$(document).ready(function() {
    let nextInstallmentData = null;
    
    // Load next installment data when modal opens
    $('#recordPaymentModal').on('show.bs.modal', function() {
        loadNextInstallment();
    });
    
    // Load next installment details
    function loadNextInstallment() {
        $.get('{{ url("client/loan/" . $allocation->loan_id . "/member-allocations/" . $allocation->id . "/next-installment") }}')
            .done(function(data) {
                nextInstallmentData = data;
                displayNextInstallment(data);
            })
            .fail(function() {
                $('#nextInstallmentInfo').hide();
                nextInstallmentData = null;
            });
    }
    
    // Display next installment information
    function displayNextInstallment(data) {
        $('#principalDue').text(formatCurrency(data.principal_due));
        $('#interestDue').text(formatCurrency(data.interest_due));
        $('#feesDue').text(formatCurrency(data.fees_due));
        $('#penaltiesDue').text(formatCurrency(data.penalties_due));
        $('#totalDue').text(formatCurrency(data.total_due));
        $('#dueDate').text(data.due_date);
        
        if (data.is_overdue) {
            $('#overdueWarning').show();
        } else {
            $('#overdueWarning').hide();
        }
        
        $('#nextInstallmentInfo').show();
    }
    
    // Pay full amount button
    $('#payFullAmount').click(function() {
        if (nextInstallmentData) {
            $('#payment_amount').val(nextInstallmentData.total_due);
            updatePaymentPreview();
        }
    });
    
    // Update payment preview when amount changes
    $('#payment_amount').on('input', function() {
        updatePaymentPreview();
    });
    
    // Update payment distribution preview
    function updatePaymentPreview() {
        const paymentAmount = parseFloat($('#payment_amount').val()) || 0;
        
        if (paymentAmount <= 0 || !nextInstallmentData) {
            $('#paymentPreview').hide();
            return;
        }
        
        let remainingPayment = paymentAmount;
        let principal = 0, interest = 0, fees = 0, penalties = 0;
        
        // Distribute payment in order: principal, interest, fees, penalties
        if (remainingPayment > 0 && nextInstallmentData.principal_due > 0) {
            principal = Math.min(remainingPayment, nextInstallmentData.principal_due);
            remainingPayment -= principal;
        }
        
        if (remainingPayment > 0 && nextInstallmentData.interest_due > 0) {
            interest = Math.min(remainingPayment, nextInstallmentData.interest_due);
            remainingPayment -= interest;
        }
        
        if (remainingPayment > 0 && nextInstallmentData.fees_due > 0) {
            fees = Math.min(remainingPayment, nextInstallmentData.fees_due);
            remainingPayment -= fees;
        }
        
        if (remainingPayment > 0 && nextInstallmentData.penalties_due > 0) {
            penalties = Math.min(remainingPayment, nextInstallmentData.penalties_due);
            remainingPayment -= penalties;
        }
        
        // Update preview
        $('#previewPrincipal').text(formatCurrency(principal));
        $('#previewInterest').text(formatCurrency(interest));
        $('#previewFees').text(formatCurrency(fees));
        $('#previewPenalties').text(formatCurrency(penalties));
        
        // Show excess payment info
        if (remainingPayment > 0) {
            $('#excessAmount').text(formatCurrency(remainingPayment));
            $('#excessPaymentInfo').show();
            $('#insufficientPaymentInfo').hide();
        } else {
            $('#excessPaymentInfo').hide();
            
            // Check if payment is insufficient
            if (paymentAmount < nextInstallmentData.total_due) {
                $('#insufficientPaymentInfo').show();
            } else {
                $('#insufficientPaymentInfo').hide();
            }
        }
        
        $('#paymentPreview').show();
    }
    
    // Format currency
    function formatCurrency(amount) {
        return parseFloat(amount).toFixed(2);
    }
    
    // Submit form via modal button
    $('.modal-footer .btn-primary').click(function() {
        $('#recordPaymentForm').submit();
    });
});
</script>
@endsection
