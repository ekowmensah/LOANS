@extends('core::layouts.master')

@section('title')
    Record Collection
@endsection

@section('styles')
<style>
    /* Mobile-First Compact Design */
    body { font-size: 14px; background: #f5f5f5; }
    
    .mobile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .mobile-header h1 {
        font-size: 1.1rem;
        margin: 0;
        font-weight: 600;
    }
    
    .progress-dots {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    
    .progress-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transition: all 0.3s;
    }
    
    .progress-dot.active {
        background: white;
        width: 24px;
        border-radius: 4px;
    }
    
    .progress-dot.completed { background: #28a745; }
    
    .compact-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    
    .section-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #667eea;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .client-info {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 0.5rem;
        border-left: 3px solid #28a745;
    }
    
    .client-info h6 {
        margin: 0 0 0.25rem 0;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .client-info small {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .form-control, .form-select {
        min-height: 44px;
        font-size: 0.95rem;
        border-radius: 6px;
        border: 1.5px solid #dee2e6;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        color: #495057;
    }
    
    .btn {
        min-height: 44px;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
    }
    
    .location-btn {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        color: #495057;
        padding: 1rem;
        text-align: center;
        width: 100%;
    }
    
    .location-btn.captured {
        background: #d4edda;
        border-color: #28a745;
        color: #28a745;
    }
    
    .sticky-footer {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1rem;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 999;
    }
    
    .mb-compact { margin-bottom: 0.75rem; }
    
    @media (min-width: 768px) {
        body { font-size: 16px; }
        .mobile-header h1 { font-size: 1.5rem; }
        .compact-card { padding: 1.5rem; margin-bottom: 1rem; }
    }
</style>
@endsection

@section('content')
    <div id="app">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-hand-holding-usd"></i> Record Collection</h1>
                <a href="{{ url('field-agent/collection') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="progress-dots">
                <div class="progress-dot" :class="{'active': currentStep === 1, 'completed': currentStep > 1}"></div>
                <div class="progress-dot" :class="{'active': currentStep === 2, 'completed': currentStep > 2}"></div>
                <div class="progress-dot" :class="{'active': currentStep === 3, 'completed': currentStep > 3}"></div>
                <div class="progress-dot" :class="{'active': currentStep === 4}"></div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="container-fluid p-2" style="padding-bottom: 80px;">
            <form method="post" action="{{ url('field-agent/collection/store') }}" enctype="multipart/form-data" id="collection-form">
                @csrf
                
                <!-- Field Agent Info -->
                @if(isset($currentFieldAgent))
                    <input type="hidden" name="field_agent_id" value="{{ $currentFieldAgent->id }}">
                    <div class="compact-card">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie fa-lg text-primary mr-2"></i>
                            <div>
                                <small class="text-muted d-block">Field Agent</small>
                                <strong style="font-size: 0.9rem;">{{ $currentFieldAgent->full_name }}</strong>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="compact-card">
                        <label class="form-label">Field Agent</label>
                        <select class="form-control" name="field_agent_id" required>
                            <option value="">Select Agent</option>
                            @foreach($fieldAgents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->agent_code }} - {{ $agent->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Error Alert -->
                <div v-if="error_message" class="alert alert-danger alert-dismissible" style="padding: 0.75rem; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-triangle"></i> @{{ error_message }}
                    <button type="button" class="close" @click="error_message = null">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- Client Search -->
                <div class="compact-card">
                    <div class="section-title">
                        <i class="fas fa-search"></i> Find Client
                    </div>
                    
                    <div class="mb-compact">
                        <label class="form-label">Savings Account Number</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   v-model="client_search" 
                                   placeholder="e.g., SA-12345"
                                   @keyup.enter="searchClient"
                                   :disabled="searching_client">
                            <div class="input-group-append">
                                <button class="btn btn-success" 
                                        type="button" 
                                        @click="searchClient" 
                                        :disabled="searching_client">
                                    <i class="fas" :class="searching_client ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="client_id" id="client_id" v-model="selected_client_id" required>
                    </div>

                    <!-- Client Found -->
                    <div v-if="client" class="client-info">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6>@{{ client.first_name }} @{{ client.last_name }}</h6>
                                <small>
                                    <i class="fas fa-phone"></i> @{{ client.mobile || 'N/A' }}<br>
                                    <i class="fas fa-piggy-bank"></i> @{{ client.savings_account_number }}
                                </small>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>

                <!-- Collection Type & Account -->
                <div class="compact-card">
                    <div class="section-title">
                        <i class="fas fa-list-alt"></i> Collection Details
                    </div>
                    
                    <div class="mb-compact">
                        <label class="form-label">Collection Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="collection_type" id="collection_type" required disabled>
                            <option value="">Select client first</option>
                            <option value="savings_deposit">💰 Savings Deposit</option>
                            <option value="loan_repayment">💳 Loan Repayment</option>
                            <option value="share_purchase">📊 Share Purchase</option>
                        </select>
                    </div>
                    
                    <div class="mb-compact">
                        <label class="form-label">Account/Loan <span class="text-danger">*</span></label>
                        <select class="form-control" name="reference_id" id="reference_id" required disabled>
                            <option value="">Select type first</option>
                        </select>
                        <div id="loan-payment-info" class="mt-2" style="display: none;"></div>
                    </div>
                </div>

                <!-- Amount & Details -->
                <div class="compact-card">
                    <div class="section-title">
                        <i class="fas fa-money-bill-wave"></i> Amount & Payment
                    </div>
                    
                    <div class="mb-compact">
                        <label class="form-label">Amount (GHS) <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="amount" 
                               id="amount" 
                               class="form-control" 
                               value="{{ old('amount') }}" 
                               min="0.01" 
                               step="0.01" 
                               placeholder="0.00"
                               required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-compact">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="collection_date" 
                                       id="collection_date" 
                                       class="form-control" 
                                       value="{{ old('collection_date', date('Y-m-d')) }}" 
                                       required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-compact">
                                <label class="form-label">Time <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="collection_time" 
                                       id="collection_time" 
                                       class="form-control" 
                                       value="{{ old('collection_time', date('H:i')) }}" 
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-compact">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-control" name="payment_method" required>
                            <option value="">Select method</option>
                            <option value="cash" selected>💵 Cash</option>
                            <option value="mobile_money">📱 Mobile Money</option>
                            <option value="bank_transfer">🏦 Bank Transfer</option>
                            <option value="cheque">📝 Cheque</option>
                        </select>
                    </div>
                    
                    <div class="mb-compact">
                        <label class="form-label">Receipt Number (Optional)</label>
                        <input type="text" 
                               name="receipt_number" 
                               class="form-control" 
                               value="{{ old('receipt_number') }}" 
                               placeholder="Auto-generated">
                    </div>
                    
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" 
                                  class="form-control" 
                                  rows="2" 
                                  placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Location -->
                <div class="compact-card">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Location (Optional)
                    </div>
                    
                    <button type="button" 
                            class="location-btn" 
                            :class="{'captured': locationCaptured}"
                            id="get-location">
                        <i class="fas" :class="locationCaptured ? 'fa-check-circle' : 'fa-crosshairs'" style="font-size: 2rem;"></i>
                        <div class="mt-2">
                            <span v-if="!locationCaptured">Tap to Capture Location</span>
                            <span v-else class="font-weight-bold">Location Captured</span>
                        </div>
                    </button>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>

            </form>
        </div>

        <!-- Sticky Footer -->
        <div class="sticky-footer">
            <button type="submit" form="collection-form" class="btn btn-primary btn-block">
                <i class="fas fa-check-circle"></i> Record Collection
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                client_search: '',
                searching_client: false,
                client: null,
                selected_client_id: '',
                error_message: null,
                currentStep: 1,
                locationCaptured: false
            },
            methods: {
                searchClient() {
                    if (!this.client_search) {
                        this.error_message = 'Please enter a savings account number';
                        return;
                    }

                    this.searching_client = true;
                    this.client = null;
                    this.selected_client_id = '';
                    this.error_message = null;

                    axios.post('{{url("field-agent/collection/search-clients")}}', {
                        search: this.client_search
                    })
                    .then(response => {
                        this.searching_client = false;
                        if (response.data.success) {
                            this.client = response.data.data;
                            this.selected_client_id = this.client.id;
                            this.error_message = null;
                            this.currentStep = 2;
                            
                            $('#collection_type')
                                .prop('disabled', false)
                                .prop('readonly', false)
                                .val('')
                                .trigger('change');
                            
                            $('#collection_type option:first').text('Select collection type');
                            $('#reference_id')
                                .html('<option value="">Select type first</option>')
                                .prop('disabled', true)
                                .val('');
                            $('#loan-payment-info').hide();
                            $('#client_id').trigger('change');
                        } else {
                            this.error_message = response.data.message || 'Client not found';
                            $('#collection_type').prop('disabled', true);
                            $('#collection_type option:first').text('Select client first');
                        }
                    })
                    .catch(error => {
                        this.searching_client = false;
                        if (error.response && error.response.data && error.response.data.message) {
                            this.error_message = error.response.data.message;
                        } else {
                            this.error_message = 'Error searching for client';
                        }
                        $('#collection_type').prop('disabled', true);
                        $('#collection_type option:first').text('Select client first');
                    });
                }
            }
        });
        
        $(document).ready(function() {
            // Get GPS location
            $('#get-location').click(function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        $('#latitude').val(position.coords.latitude);
                        $('#longitude').val(position.coords.longitude);
                        app.locationCaptured = true;
                        app.currentStep = 4;
                    }, function(error) {
                        alert('Error getting location: ' + error.message);
                    });
                } else {
                    alert('Geolocation not supported');
                }
            });

            // Load accounts
            function loadAccounts() {
                var clientId = $('#client_id').val();
                var type = $('#collection_type').val();
                
                if (clientId && type) {
                    $('#reference_id').html('<option value="">Loading...</option>');
                    
                    $.ajax({
                        url: '{{url("field-agent/collection/get-client-accounts")}}',
                        type: 'GET',
                        data: { client_id: clientId, type: type },
                        success: function(response) {
                            if (response.success) {
                                var options = '<option value="">Select an account</option>';
                                $.each(response.data, function(key, value) {
                                    options += '<option value="' + value.id + '">' + value.text + '</option>';
                                });
                                $('#reference_id').html(options).prop('disabled', false);
                                app.currentStep = 3;
                            } else {
                                $('#reference_id').html('<option value="">No accounts found</option>').prop('disabled', true);
                                app.error_message = response.message;
                            }
                        },
                        error: function(xhr) {
                            $('#reference_id').html('<option value="">Error loading accounts</option>').prop('disabled', true);
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                app.error_message = xhr.responseJSON.message;
                            }
                        }
                    });
                }
            }

            $('#collection_type').change(function() {
                $('#reference_id').html('<option value="">Select type first</option>').prop('disabled', true).val('');
                $('#loan-payment-info').hide();
                loadAccounts();
            });

            $('#client_id').change(function() {
                if ($(this).val()) {
                    loadAccounts();
                }
            });

            // Load loan payment info
            $('#reference_id').change(function() {
                var loanId = $(this).val();
                var type = $('#collection_type').val();
                
                if (type === 'loan_repayment' && loanId) {
                    $.ajax({
                        url: '{{url("field-agent/collection/get-loan-payment-info")}}',
                        type: 'GET',
                        data: { loan_id: loanId },
                        success: function(response) {
                            if (response.success) {
                                var info = '<div class="alert alert-info" style="padding: 0.5rem; font-size: 0.8rem;">';
                                info += '<strong>Loan Info:</strong><br>';
                                info += 'Outstanding: <strong>GHS ' + response.arrears_formatted + '</strong><br>';
                                info += 'Next Due: <strong>' + response.next_due_date + '</strong>';
                                info += '</div>';
                                $('#loan-payment-info').html(info).show();
                            }
                        }
                    });
                } else {
                    $('#loan-payment-info').hide();
                }
            });
        });
    </script>
@endsection
