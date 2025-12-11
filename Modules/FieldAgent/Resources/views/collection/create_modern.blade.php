@extends('core::layouts.master')

@section('title')
    Record Collection
@endsection

@section('styles')
<style>
    .collection-wizard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 10px 10px 0 0;
        color: white;
    }
    
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .wizard-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        border: 3px solid rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-size: 1.2rem;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .wizard-step.active .step-circle {
        background: white;
        color: #667eea;
        border-color: white;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4);
    }
    
    .wizard-step.completed .step-circle {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .step-label {
        font-size: 0.875rem;
        opacity: 0.8;
    }
    
    .wizard-step.active .step-label {
        opacity: 1;
        font-weight: 600;
    }
    
    .wizard-line {
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.3);
        z-index: 0;
    }
    
    .collection-card {
        border: none;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        border-radius: 0 0 10px 10px;
    }
    
    .info-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }
    
    .client-badge {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }
    
    .client-badge .badge-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.5rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-search {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        font-weight: 600;
    }
    
    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
        color: white;
    }
    
    .location-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border: 2px dashed #dee2e6;
    }
    
    .location-card.captured {
        background: #d4edda;
        border-color: #28a745;
    }
    
    .section-header {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .section-header i {
        color: #667eea;
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-hand-holding-usd"></i> Record Collection</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/collection') }}">Collections</a></li>
                        <li class="breadcrumb-item active">Record</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <div class="container-fluid">
            <!-- Wizard Header -->
            <div class="collection-wizard">
                <div class="wizard-line"></div>
                <div class="wizard-steps">
                    <div class="wizard-step" :class="{'active': currentStep === 1, 'completed': currentStep > 1}">
                        <div class="step-circle">
                            <i class="fas" :class="currentStep > 1 ? 'fa-check' : 'fa-user'"></i>
                        </div>
                        <div class="step-label">Client Selection</div>
                    </div>
                    <div class="wizard-step" :class="{'active': currentStep === 2, 'completed': currentStep > 2}">
                        <div class="step-circle">
                            <i class="fas" :class="currentStep > 2 ? 'fa-check' : 'fa-list'"></i>
                        </div>
                        <div class="step-label">Collection Type</div>
                    </div>
                    <div class="wizard-step" :class="{'active': currentStep === 3, 'completed': currentStep > 3}">
                        <div class="step-circle">
                            <i class="fas" :class="currentStep > 3 ? 'fa-check' : 'fa-money-bill-wave'"></i>
                        </div>
                        <div class="step-label">Amount & Details</div>
                    </div>
                    <div class="wizard-step" :class="{'active': currentStep === 4}">
                        <div class="step-circle">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card collection-card">
                <form method="post" action="{{ url('field-agent/collection/store') }}" enctype="multipart/form-data" id="collection-form">
                    @csrf
                    <div class="card-body p-4">
                        
                        <!-- Field Agent Info (Always Visible) -->
                        <div class="info-card">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <i class="fas fa-user-tie fa-2x text-primary"></i>
                                </div>
                                <div class="col-md-11">
                                    <strong>Field Agent:</strong>
                                    @if(isset($currentFieldAgent))
                                        <span class="text-primary">{{ $currentFieldAgent->agent_code }} - {{ $currentFieldAgent->full_name }}</span>
                                        <input type="hidden" name="field_agent_id" value="{{ $currentFieldAgent->id }}">
                                    @else
                                        <select class="form-control form-control-sm d-inline-block w-auto ml-2" name="field_agent_id" id="field_agent_id" required>
                                            <option value="">Select Agent</option>
                                            @foreach($fieldAgents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->agent_code }} - {{ $agent->full_name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Error Alert -->
                        <div v-if="error_message" class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Error!</strong> @{{ error_message }}
                            <button type="button" class="close" @click="error_message = null">
                                <span>&times;</span>
                            </button>
                        </div>

                        <!-- Step 1: Client Selection -->
                        <div class="step-content">
                            <h5 class="section-header">
                                <i class="fas fa-search"></i> Step 1: Find Client
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Search by Savings Account Number</label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                            </div>
                                            <input type="text" 
                                                   class="form-control" 
                                                   v-model="client_search" 
                                                   placeholder="Enter savings account number (e.g., SA-12345)"
                                                   @keyup.enter="searchClient"
                                                   :disabled="searching_client">
                                            <div class="input-group-append">
                                                <button class="btn btn-search btn-lg px-4" 
                                                        type="button" 
                                                        @click="searchClient" 
                                                        :disabled="searching_client">
                                                    <i class="fas" :class="searching_client ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                                    <span v-if="!searching_client"> Search</span>
                                                    <span v-else> Searching...</span>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="client_id" id="client_id" v-model="selected_client_id" required>
                                    </div>

                                    <!-- Client Found Badge -->
                                    <div v-if="client" class="client-badge">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="badge-icon">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1 font-weight-bold">@{{ client.first_name }} @{{ client.last_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone mr-1"></i> @{{ client.mobile || 'N/A' }}
                                                    <span class="mx-2">|</span>
                                                    <i class="fas fa-piggy-bank mr-1"></i> @{{ client.savings_account_number }}
                                                </small>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check-circle fa-2x text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Step 2: Collection Type & Account -->
                        <div class="step-content">
                            <h5 class="section-header">
                                <i class="fas fa-list-alt"></i> Step 2: Select Collection Type & Account
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Collection Type <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-lg" name="collection_type" id="collection_type" required disabled>
                                            <option value="">Select client first</option>
                                            <option value="savings_deposit">💰 Savings Deposit</option>
                                            <option value="loan_repayment">💳 Loan Repayment</option>
                                            <option value="share_purchase">📊 Share Purchase</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Select Account/Loan <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-lg" name="reference_id" id="reference_id" required disabled>
                                            <option value="">Select type first</option>
                                        </select>
                                        <div id="loan-payment-info" class="mt-2" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Step 3: Amount & Details -->
                        <div class="step-content">
                            <h5 class="section-header">
                                <i class="fas fa-money-bill-wave"></i> Step 3: Enter Collection Details
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Amount <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">GHS</span>
                                            </div>
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
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Collection Date <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               name="collection_date" 
                                               id="collection_date" 
                                               class="form-control form-control-lg" 
                                               value="{{ old('collection_date', date('Y-m-d')) }}" 
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Collection Time <span class="text-danger">*</span></label>
                                        <input type="time" 
                                               name="collection_time" 
                                               id="collection_time" 
                                               class="form-control form-control-lg" 
                                               value="{{ old('collection_time', date('H:i')) }}" 
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-lg" name="payment_method" id="payment_method" required>
                                            <option value="">Select method</option>
                                            <option value="cash" selected>💵 Cash</option>
                                            <option value="mobile_money">📱 Mobile Money</option>
                                            <option value="bank_transfer">🏦 Bank Transfer</option>
                                            <option value="cheque">📝 Cheque</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Receipt Number</label>
                                        <input type="text" 
                                               name="receipt_number" 
                                               id="receipt_number" 
                                               class="form-control form-control-lg" 
                                               value="{{ old('receipt_number') }}" 
                                               placeholder="Auto-generated if left blank">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Notes/Comments</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Add any additional notes about this collection...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Step 4: Location -->
                        <div class="step-content">
                            <h5 class="section-header">
                                <i class="fas fa-map-marker-alt"></i> Step 4: Location (Optional)
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="location-card" :class="{'captured': locationCaptured}">
                                        <div class="text-center">
                                            <i class="fas fa-map-marked-alt fa-3x mb-3" :class="locationCaptured ? 'text-success' : 'text-muted'"></i>
                                            <h6 v-if="!locationCaptured">Capture Collection Location</h6>
                                            <h6 v-else class="text-success">
                                                <i class="fas fa-check-circle"></i> Location Captured
                                            </h6>
                                            <button type="button" 
                                                    class="btn btn-lg mt-2" 
                                                    :class="locationCaptured ? 'btn-success' : 'btn-outline-primary'"
                                                    id="get-location">
                                                <i class="fas" :class="locationCaptured ? 'fa-check' : 'fa-crosshairs'"></i>
                                                <span v-if="!locationCaptured"> Get Current Location</span>
                                                <span v-else> Location Captured</span>
                                            </button>
                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">
                                            <p class="text-muted small mt-2 mb-0">GPS coordinates will be recorded for verification purposes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Form Actions -->
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ url('field-agent/collection') }}" class="btn btn-lg btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-check-circle"></i> Record Collection
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        console.log('Field Agent Collection Script Loaded');
        
        // Vue.js App
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
                        this.error_message = 'Please enter a savings account number to search.';
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
                            
                            // Enable collection type dropdown
                            $('#collection_type').prop('disabled', false);
                            $('#collection_type option:first').text('Select collection type');
                            
                            // Reset fields
                            $('#collection_type').val('').trigger('change');
                            $('#reference_id').html('<option value="">Select type first</option>').prop('disabled', true);
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
                        console.error(error);
                        if (error.response && error.response.data && error.response.data.message) {
                            this.error_message = error.response.data.message;
                        } else {
                            this.error_message = 'Error searching for client. Please try again.';
                        }
                        $('#collection_type').prop('disabled', true);
                        $('#collection_type option:first').text('Select client first');
                    });
                }
            }
        });
        
        $(document).ready(function() {
            console.log('Document ready - initializing...');
            
            // Initialize select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Select an option',
                    allowClear: true
                });
            }

            // Get GPS location
            $('#get-location').click(function() {
                if (navigator.geolocation) {
                    $(this).html('<i class="fas fa-spinner fa-spin"></i> Getting location...');
                    var btn = $(this);
                    
                    navigator.geolocation.getCurrentPosition(function(position) {
                        $('#latitude').val(position.coords.latitude);
                        $('#longitude').val(position.coords.longitude);
                        app.locationCaptured = true;
                        app.currentStep = 4;
                    }, function(error) {
                        alert('Error getting location: ' + error.message);
                        btn.html('<i class="fas fa-crosshairs"></i> Get Current Location');
                    });
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });

            // Load accounts when collection type changes
            function loadAccounts() {
                var clientId = $('#client_id').val();
                var type = $('#collection_type').val();
                
                if (clientId && type) {
                    $('#reference_id').html('<option value="">Loading...</option>');
                    
                    $.ajax({
                        url: '{{url("field-agent/collection/get-client-accounts")}}',
                        type: 'GET',
                        data: {
                            client_id: clientId,
                            type: type
                        },
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
                $('#reference_id').html('<option value="">Select type first</option>').prop('disabled', true);
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
                                var info = '<div class="alert alert-info">';
                                info += '<strong>Loan Payment Information:</strong><br>';
                                info += 'Outstanding Balance: <strong>GHS ' + response.data.outstanding + '</strong><br>';
                                info += 'Next Payment Due: <strong>' + response.data.next_payment_date + '</strong><br>';
                                info += 'Amount Due: <strong>GHS ' + response.data.amount_due + '</strong>';
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
