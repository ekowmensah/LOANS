@extends('core::layouts.master')

@section('title')
    Edit {{ trans_choice('fieldagent::general.collection', 1) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit {{ trans_choice('fieldagent::general.collection', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/collection') }}">{{ trans_choice('fieldagent::general.collection', 2) }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Collection #{{ $collection->receipt_number }}</h3>
                <div class="card-tools">
                    <a href="{{ url('field-agent/collection') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> {{ trans_choice('core::general.back', 1) }}
                    </a>
                </div>
            </div>
            <form method="post" action="{{ url('field-agent/collection/' . $collection->id . '/update') }}" enctype="multipart/form-data" id="collection-form">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You can only edit collections that are in <strong>Pending</strong> status.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field_agent_id" class="control-label">{{ trans_choice('fieldagent::general.field_agent', 1) }} <span class="text-danger">*</span></label>
                                @if(isset($currentFieldAgent))
                                    {{-- Field agent is logged in, show as readonly --}}
                                    <input type="text" class="form-control" value="{{ $currentFieldAgent->agent_code }} - {{ $currentFieldAgent->full_name }}" readonly>
                                    <input type="hidden" name="field_agent_id" value="{{ $currentFieldAgent->id }}">
                                    <small class="form-text text-muted">You are logged in as this field agent</small>
                                @else
                                    {{-- Admin user, show dropdown --}}
                                    <select class="form-control select2" name="field_agent_id" id="field_agent_id" required>
                                        <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                        @foreach($fieldAgents as $agent)
                                            <option value="{{ $agent->id }}" {{ old('field_agent_id', $collection->field_agent_id) == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->agent_code }} - {{ $agent->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('field_agent_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id" class="control-label">{{ trans_choice('client::general.client', 1) }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="client_id" id="client_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $collection->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->first_name }} {{ $client->last_name }} ({{ $client->mobile ?? $client->account_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Type to search by name or phone</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="collection_type" class="control-label">{{ trans_choice('fieldagent::general.collection_type', 1) }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="collection_type" id="collection_type" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    <option value="savings_deposit" {{ old('collection_type') == 'savings_deposit' ? 'selected' : '' }}>Savings Deposit</option>
                                    <option value="loan_repayment" {{ old('collection_type') == 'loan_repayment' ? 'selected' : '' }}>Loan Repayment</option>
                                    <option value="share_purchase" {{ old('collection_type') == 'share_purchase' ? 'selected' : '' }}>Share Purchase</option>
                                </select>
                                @error('collection_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reference_id" class="control-label">Account/Loan <span class="text-danger">*</span></label>
                                <select class="form-control" name="reference_id" id="reference_id" required disabled>
                                    <option value="">Select client and type first</option>
                                </select>
                                @error('reference_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div id="loan-payment-info" class="mt-2" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount" class="control-label">{{ trans_choice('core::general.amount', 1) }} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" min="0.01" step="0.01" required>
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="collection_date" class="control-label">{{ trans_choice('fieldagent::general.collection_date', 1) }} <span class="text-danger">*</span></label>
                                <input type="date" name="collection_date" id="collection_date" class="form-control" value="{{ old('collection_date', date('Y-m-d')) }}" required>
                                @error('collection_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="collection_time" class="control-label">{{ trans_choice('fieldagent::general.collection_time', 1) }} <span class="text-danger">*</span></label>
                                <input type="time" name="collection_time" id="collection_time" class="form-control" value="{{ old('collection_time', date('H:i')) }}" required>
                                @error('collection_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method" class="control-label">{{ trans_choice('fieldagent::general.payment_method', 1) }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_method" id="payment_method" required>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo_proof" class="control-label">{{ trans_choice('fieldagent::general.photo_proof', 1) }}</label>
                                <input type="file" name="photo_proof" id="photo_proof" class="form-control-file" accept="image/*">
                                <small class="form-text text-muted">Upload receipt or payment proof (Max: 5MB)</small>
                                @error('photo_proof')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h3 class="card-title">{{ trans_choice('fieldagent::general.location', 1) }} (GPS)</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-sm btn-primary" id="get-location">
                                            <i class="fas fa-map-marker-alt"></i> Get Current Location
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="latitude">Latitude</label>
                                                <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="location_address">Address</label>
                                                <input type="text" name="location_address" id="location_address" class="form-control" value="{{ old('location_address') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="control-label">{{ trans_choice('core::general.notes', 1) }}</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Record Collection
                    </button>
                    <a href="{{ url('field-agent/collection') }}" class="btn btn-default">
                        {{ trans_choice('core::general.cancel', 1) }}
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        console.log('Field Agent Collection Script Loaded');
        
        $(document).ready(function() {
            console.log('Document ready - initializing...');
            
            // Initialize select2 only if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap4'
                });
                console.log('Select2 initialized');
            } else {
                console.log('Select2 not available, using standard dropdowns');
            }

            // Get GPS location
            $('#get-location').click(function() {
                if (navigator.geolocation) {
                    $(this).html('<i class="fas fa-spinner fa-spin"></i> Getting location...');
                    var btn = $(this);
                    
                    navigator.geolocation.getCurrentPosition(function(position) {
                        $('#latitude').val(position.coords.latitude);
                        $('#longitude').val(position.coords.longitude);
                        btn.html('<i class="fas fa-check"></i> Location Captured');
                        btn.removeClass('btn-primary').addClass('btn-success');
                    }, function(error) {
                        alert('Error getting location: ' + error.message);
                        btn.html('<i class="fas fa-map-marker-alt"></i> Get Current Location');
                    });
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });

            // Function to load accounts
            function loadAccounts() {
                var clientId = $('#client_id').val();
                var type = $('#collection_type').val();
                
                console.log('Loading accounts - Client ID:', clientId, 'Type:', type);
                
                if (clientId && type) {
                    // Show loading state
                    $('#reference_id').html('<option value="">Loading...</option>');
                    $('#reference_id').prop('disabled', true);
                    
                    $.ajax({
                        url: '{{ url("field-agent/collection/get-client-accounts") }}',
                        type: 'GET',
                        data: {
                            client_id: clientId,
                            type: type
                        },
                        beforeSend: function() {
                            console.log('AJAX request started...');
                        },
                        success: function(data) {
                            console.log('AJAX response:', data);
                            $('#reference_id').html('<option value="">Select account</option>');
                            if (data && data.length > 0) {
                                $.each(data, function(index, account) {
                                    $('#reference_id').append('<option value="' + account.id + '">' + account.name + ' (Balance: ' + account.balance + ')</option>');
                                });
                                $('#reference_id').prop('disabled', false);
                            } else {
                                $('#reference_id').html('<option value="">No accounts found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', xhr.responseText);
                            console.error('Status:', status);
                            console.error('Error:', error);
                            $('#reference_id').html('<option value="">Error loading accounts</option>');
                            alert('Error loading accounts. Check console for details.');
                        }
                    });
                } else {
                    $('#reference_id').html('<option value="">Select client and type first</option>');
                    $('#reference_id').prop('disabled', true);
                }
            }
            
            // Load accounts when client or type changes
            $('#client_id, #collection_type').on('change', function() {
                console.log('Dropdown changed - Client:', $('#client_id').val(), 'Type:', $('#collection_type').val());
                loadAccounts();
                $('#loan-payment-info').hide();
            });
            
            // Load loan payment details when loan is selected
            $('#reference_id').on('change', function() {
                var loanId = $(this).val();
                var type = $('#collection_type').val();
                var clientId = $('#client_id').val();
                
                if (type === 'loan_repayment' && loanId) {
                    $.ajax({
                        url: '{{ url("field-agent/collection/get-loan-payment-info") }}',
                        type: 'GET',
                        data: { 
                            loan_id: loanId,
                            client_id: clientId
                        },
                        success: function(data) {
                            if (data.success) {
                                var html = '<div class="alert alert-info">';
                                html += '<strong><i class="fas fa-info-circle"></i> Expected Payment:</strong><br>';
                                
                                if (data.arrears > 0) {
                                    html += '<span class="text-danger"><strong>Arrears:</strong> ' + data.arrears_formatted + '</span><br>';
                                }
                                
                                if (data.next_payment > 0) {
                                    html += '<strong>Next Payment:</strong> ' + data.next_payment_formatted + ' (Due: ' + data.next_due_date + ')<br>';
                                }
                                
                                html += '<hr class="my-2">';
                                html += '<strong style="color: #ffdd61; font-size: 1.1em;">Total Expected: ' + data.total_expected_formatted + '</strong>';
                                html += '</div>';
                                
                                $('#loan-payment-info').html(html).show();
                                
                                // Auto-fill amount with expected payment
                                $('#amount').val(data.total_expected);
                            } else {
                                $('#loan-payment-info').hide();
                            }
                        },
                        error: function() {
                            $('#loan-payment-info').hide();
                        }
                    });
                } else {
                    $('#loan-payment-info').hide();
                }
            });
        });
    </script>
@endsection
