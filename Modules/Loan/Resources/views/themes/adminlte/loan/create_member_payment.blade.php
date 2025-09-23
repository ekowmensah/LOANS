@extends('core::layouts.master')
@section('title')
    Record Member Payment
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h6 class="box-title">
                Record Payment - {{$allocation->client->first_name}} {{$allocation->client->last_name}}
            </h6>
            <div class="box-tools pull-right">
                <a href="{{url('loan/'.$loan->id.'/member-allocations/'.$allocation->id)}}" class="btn btn-info btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="box-body">
            <form method="post" action="{{url('loan/'.$loan->id.'/member-allocations/'.$allocation->id.'/payment/store')}}">
                {{csrf_field()}}
                
                <div class="row">
                    <!-- Payment Information -->
                    <div class="col-md-8">
                        <div class="box box-solid">
                            <div class="box-header">
                                <h4 class="box-title">Payment Information</h4>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount" class="control-label">Payment Amount <span class="text-red">*</span></label>
                                            <input type="number" name="amount" id="amount" class="form-control" 
                                                   value="{{old('amount')}}" step="0.01" min="0.01" required>
                                            @if($errors->has('amount'))
                                                <span class="help-block text-red">{{$errors->first('amount')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_date" class="control-label">Payment Date <span class="text-red">*</span></label>
                                            <input type="date" name="payment_date" id="payment_date" class="form-control" 
                                                   value="{{old('payment_date', date('Y-m-d'))}}" required>
                                            @if($errors->has('payment_date'))
                                                <span class="help-block text-red">{{$errors->first('payment_date')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_type_id" class="control-label">Payment Method <span class="text-red">*</span></label>
                                            <select name="payment_type_id" id="payment_type_id" class="form-control" required>
                                                <option value="">Select Payment Method</option>
                                                @foreach($payment_types as $type)
                                                    <option value="{{$type->id}}" {{old('payment_type_id') == $type->id ? 'selected' : ''}}>
                                                        {{$type->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('payment_type_id'))
                                                <span class="help-block text-red">{{$errors->first('payment_type_id')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="receipt" class="control-label">Receipt Number</label>
                                            <input type="text" name="receipt" id="receipt" class="form-control" 
                                                   value="{{old('receipt')}}" placeholder="Receipt/Reference Number">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cheque_number" class="control-label">Cheque Number</label>
                                            <input type="text" name="cheque_number" id="cheque_number" class="form-control" 
                                                   value="{{old('cheque_number')}}" placeholder="Cheque Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_number" class="control-label">Account Number</label>
                                            <input type="text" name="account_number" id="account_number" class="form-control" 
                                                   value="{{old('account_number')}}" placeholder="Account Number">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_name" class="control-label">Bank Name</label>
                                            <input type="text" name="bank_name" id="bank_name" class="form-control" 
                                                   value="{{old('bank_name')}}" placeholder="Bank Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="routing_code" class="control-label">Routing Code</label>
                                            <input type="text" name="routing_code" id="routing_code" class="form-control" 
                                                   value="{{old('routing_code')}}" placeholder="Routing Code">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description" class="control-label">Description/Notes</label>
                                    <textarea name="description" id="description" class="form-control" rows="3" 
                                              placeholder="Payment description or notes">{{old('description')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Allocation Summary -->
                    <div class="col-md-4">
                        @if($next_schedule)
                        <div class="box box-solid">
                            <div class="box-header">
                                <h4 class="box-title">Next Payment Due</h4>
                            </div>
                            <div class="box-body">
                                <div class="alert alert-info">
                                    <h4><i class="fa fa-calendar"></i> Installment #{{$next_schedule->installment_number}}</h4>
                                    <p><strong>Due Date:</strong> {{date('M d, Y', strtotime($next_schedule->due_date))}}</p>
                                    <p><strong>Expected Amount:</strong> <span class="text-green">{{number_format($next_schedule->total_due, 2)}}</span></p>
                                    <hr>
                                    <small>
                                        Principal: {{number_format($next_schedule->principal_due, 2)}} | 
                                        Interest: {{number_format($next_schedule->interest_due, 2)}}
                                        @if($next_schedule->fees_due > 0)
                                            | Fees: {{number_format($next_schedule->fees_due, 2)}}
                                        @endif
                                        @if($next_schedule->penalties_due > 0)
                                            | Penalties: {{number_format($next_schedule->penalties_due, 2)}}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="box box-solid">
                            <div class="box-header">
                                <h4 class="box-title">Allocation Summary</h4>
                            </div>
                            <div class="box-body">
                                <table class="table table-condensed">
                                    <tr>
                                        <td><strong>Member:</strong></td>
                                        <td>{{$allocation->client->first_name}} {{$allocation->client->last_name}}</td>
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
                                        <td><strong>Total Paid:</strong></td>
                                        <td>{{number_format($allocation->total_paid, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Outstanding:</strong></td>
                                        <td><strong style="color: red">{{number_format($allocation->outstanding_balance, 2)}}</strong></td>
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
                        </div>
                        
                        <!-- Quick Amount Buttons -->
                        <div class="box box-solid">
                            <div class="box-header">
                                <h4 class="box-title">Quick Amounts</h4>
                            </div>
                            <div class="box-body">
                                @if($next_schedule)
                                <button type="button" class="btn btn-sm btn-success btn-block" onclick="setAmount({{$next_schedule->total_due}})">
                                    Next Payment: {{number_format($next_schedule->total_due, 2)}}
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-default btn-block" onclick="setAmount({{$allocation->outstanding_balance}})">
                                    Full Outstanding: {{number_format($allocation->outstanding_balance, 2)}}
                                </button>
                                @php
                                    $halfAmount = $allocation->outstanding_balance / 2;
                                    $quarterAmount = $allocation->outstanding_balance / 4;
                                @endphp
                                <button type="button" class="btn btn-sm btn-default btn-block" onclick="setAmount({{$halfAmount}})">
                                    Half Amount: {{number_format($halfAmount, 2)}}
                                </button>
                                <button type="button" class="btn btn-sm btn-default btn-block" onclick="setAmount({{$quarterAmount}})">
                                    Quarter Amount: {{number_format($quarterAmount, 2)}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Record Payment
                            </button>
                            <a href="{{url('loan/'.$loan->id.'/member-allocations/'.$allocation->id)}}" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount.toFixed(2);
        }
    </script>
@endsection
