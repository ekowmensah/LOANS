@extends('core::layouts.master')
@section('title')
    {{ trans_choice('loan::general.group',1) }} {{ trans_choice('loan::general.payment',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('loan::general.group',1) }} {{ trans_choice('loan::general.payment',1) }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/show')}}">{{ trans_choice('loan::general.loan',1) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('loan::general.group',1) }} {{ trans_choice('loan::general.payment',1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <!-- Loan Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center border-right">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h6 class="text-muted mb-1">Group</h6>
                                <a href="{{url('client/group/'.$loan->group_id.'/show')}}" class="font-weight-bold">{{$loan->group->name}}</a>
                            </div>
                            <div class="col-md-3 text-center border-right">
                                <i class="fas fa-file-invoice fa-2x text-info mb-2"></i>
                                <h6 class="text-muted mb-1">Loan #</h6>
                                <h5 class="font-weight-bold mb-0">{{$loan->id}}</h5>
                            </div>
                            <div class="col-md-3 text-center border-right">
                                <i class="fas fa-coins fa-2x text-success mb-2"></i>
                                <h6 class="text-muted mb-1">Principal</h6>
                                <h5 class="font-weight-bold mb-0">{{number_format($loan->principal, $loan->decimals)}}</h5>
                            </div>
                            <div class="col-md-3 text-center">
                                <i class="fas fa-balance-scale fa-2x text-warning mb-2"></i>
                                <h6 class="text-muted mb-1">Balance</h6>
                                <h5 class="font-weight-bold mb-0">{{number_format($balance, $loan->decimals)}}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="post" action="{{ url('loan/'.$loan->id.'/group-payment/store') }}" @submit="handleSubmit">
                    {{csrf_field()}}
                    
                    <!-- Payment Details -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4"><i class="fas fa-calendar-check text-primary"></i> Payment Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Payment Date</label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <flat-pickr v-model="date" name="date"
                                                       class="form-control form-control-lg @error('date') is-invalid @enderror" required>
                                            </flat-pickr>
                                        </div>
                                        @error('date')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Notes (Optional)</label>
                                        <textarea v-model="notes" class="form-control form-control-lg" rows="1"
                                                  placeholder="Add payment notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Member Payments -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-users text-primary"></i> Member Payments</h5>
                                <button type="button" class="btn btn-primary" @click="addMember">
                                    <i class="fas fa-plus-circle"></i> Add Member
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Empty State -->
                            <div v-if="members.length === 0" class="text-center py-5">
                                <i class="fas fa-user-plus fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No members added yet</h5>
                                <p class="text-muted">Click "Add Member" to start recording payments</p>
                            </div>
                            
                            <!-- Member Payment Cards -->
                            <div v-for="(member, index) in members" :key="index" class="member-card mb-3">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <!-- Member Selection -->
                                            <div class="col-md-3">
                                                <label class="small font-weight-bold text-muted">MEMBER</label>
                                                <select v-model="member.client_id" class="form-control" required @change="loadMemberDetails(index)">
                                                    <option value="">Select Member</option>
                                                    @foreach($allocations as $allocation)
                                                    <option value="{{$allocation->client_id}}" 
                                                            data-allocated="{{$allocation->allocated_amount}}"
                                                            data-balance="{{$allocation->outstanding_balance}}"
                                                            data-allocation-id="{{$allocation->id}}"
                                                            data-name="{{$allocation->client->first_name}} {{$allocation->client->last_name}}"
                                                            data-savings-balance="{{$allocation->savings_balance ?? 0}}">
                                                        {{$allocation->client->first_name}} {{$allocation->client->last_name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Payment Source -->
                                            <div class="col-md-2" v-if="member.client_id">
                                                <label class="small font-weight-bold text-muted">SOURCE</label>
                                                <select v-model="member.payment_source" class="form-control" required>
                                                    <option value="cash">💵 Cash</option>
                                                    <option value="savings">🏦 Savings</option>
                                                </select>
                                                <small class="text-info" v-if="member.payment_source === 'savings'">
                                                    Bal: @{{ member.savings_balance || 0 }}
                                                </small>
                                            </div>

                                            <!-- Payment Type (Cash only) -->
                                            <div class="col-md-2" v-if="member.client_id && member.payment_source === 'cash'">
                                                <label class="small font-weight-bold text-muted">TYPE</label>
                                                <v-select label="name" :options="payment_types"
                                                          :reduce="payment_type => payment_type.id"
                                                          v-model="member.payment_type_id"
                                                          placeholder="Type">
                                                </v-select>
                                            </div>

                                            <!-- Amount -->
                                            <div class="col-md-3" v-if="member.client_id">
                                                <label class="small font-weight-bold text-muted">AMOUNT</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" v-model="member.amount" 
                                                           class="form-control" :max="member.balance"
                                                           placeholder="0.00" required>
                                                </div>
                                                <small class="text-muted">Balance: @{{ member.balance }}</small>
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="col-md-2" v-if="member.client_id">
                                                <label class="small">&nbsp;</label>
                                                <button type="button" class="btn btn-outline-danger btn-block" @click="removeMember(index)">
                                                    <i class="fas fa-times"></i> Remove
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Member Info -->
                                        <div v-if="member.client_id" class="mt-2 pt-2 border-top">
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> <strong>@{{ member.name }}</strong> • 
                                                Allocated: <strong>@{{ member.allocated }}</strong> • 
                                                Outstanding: <strong class="text-danger">@{{ member.balance }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total Summary -->
                            <div v-if="members.length > 0" class="alert alert-primary mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-calculator"></i> Total Payment</h5>
                                    <h3 class="mb-0 font-weight-bold">@{{ totalPayment.toFixed(2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between">
                                <button type="button" onclick="window.history.back()" class="btn btn-lg btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-lg btn-success px-5" :disabled="members.length === 0">
                                    <i class="fas fa-check-circle"></i> Process Group Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('styles')
    <style>
        .card.shadow-sm {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
            border: none;
            margin-bottom: 1.5rem;
        }
        .member-card .card {
            transition: all 0.3s ease;
            border-color: #e0e0e0;
        }
        .member-card .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        .input-group-lg .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        .input-group-lg .form-control {
            border-left: none;
        }
        .input-group-lg .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        .border-right {
            border-right: 1px solid #dee2e6 !important;
        }
        .alert-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
    </style>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: "#app",
            data: {
                date: "{{date('Y-m-d')}}",
                notes: "",
                payment_types: {!! json_encode($payment_types) !!},
                members: [],
            },
            computed: {
                totalPayment() {
                    return this.members.reduce((total, member) => {
                        return total + (parseFloat(member.amount) || 0);
                    }, 0);
                }
            },
            methods: {
                addMember() {
                    this.members.push({
                        client_id: '',
                        name: '',
                        allocated: 0,
                        balance: 0,
                        savings_balance: 0,
                        allocation_id: '',
                        payment_source: 'cash',
                        payment_type_id: '',
                        amount: ''
                    });
                },
                removeMember(index) {
                    this.members.splice(index, 1);
                },
                loadMemberDetails(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (option.value) {
                        this.members[index].name = option.getAttribute('data-name');
                        this.members[index].allocated = parseFloat(option.getAttribute('data-allocated'));
                        this.members[index].balance = parseFloat(option.getAttribute('data-balance'));
                        this.members[index].savings_balance = parseFloat(option.getAttribute('data-savings-balance'));
                        this.members[index].allocation_id = option.getAttribute('data-allocation-id');
                    }
                },
                handleSubmit(e) {
                    e.preventDefault();
                    
                    const form = e.target;
                    
                    // Validate that at least one member has been added
                    if (this.members.length === 0) {
                        alert('Please add at least one member payment');
                        return false;
                    }
                    
                    // Validate that all members have required fields
                    let hasValidPayment = false;
                    for (let member of this.members) {
                        if (member.client_id && member.amount > 0) {
                            hasValidPayment = true;
                            // Validate payment type for cash payments
                            if (member.payment_source === 'cash' && !member.payment_type_id) {
                                alert('Please select payment type for cash payments');
                                return false;
                            }
                        }
                    }
                    
                    if (!hasValidPayment) {
                        alert('Please enter at least one payment amount');
                        return false;
                    }
                    
                    // Remove any previously added hidden inputs
                    const existingInputs = form.querySelectorAll('input[data-vue-generated]');
                    existingInputs.forEach(input => input.remove());
                    
                    // Add hidden inputs for each member
                    this.members.forEach((member, index) => {
                        if (member.client_id && member.amount > 0) {
                            // Add payment amount
                            const amountInput = document.createElement('input');
                            amountInput.type = 'hidden';
                            amountInput.name = `payments[${member.client_id}]`;
                            amountInput.value = member.amount;
                            amountInput.setAttribute('data-vue-generated', 'true');
                            form.appendChild(amountInput);
                            
                            // Add allocation ID
                            const allocationInput = document.createElement('input');
                            allocationInput.type = 'hidden';
                            allocationInput.name = `allocation_ids[${member.client_id}]`;
                            allocationInput.value = member.allocation_id;
                            allocationInput.setAttribute('data-vue-generated', 'true');
                            form.appendChild(allocationInput);
                            
                            // Add payment source
                            const sourceInput = document.createElement('input');
                            sourceInput.type = 'hidden';
                            sourceInput.name = `payment_sources[${member.client_id}]`;
                            sourceInput.value = member.payment_source;
                            sourceInput.setAttribute('data-vue-generated', 'true');
                            form.appendChild(sourceInput);
                            
                            // Add payment type if cash
                            if (member.payment_source === 'cash' && member.payment_type_id) {
                                const typeInput = document.createElement('input');
                                typeInput.type = 'hidden';
                                typeInput.name = `payment_types[${member.client_id}]`;
                                typeInput.value = member.payment_type_id;
                                typeInput.setAttribute('data-vue-generated', 'true');
                                form.appendChild(typeInput);
                            }
                        }
                    });
                    
                    // Debug: Log what we're submitting
                    console.log('Submitting form with members:', this.members);
                    console.log('Form data:', new FormData(form));
                    
                    // Now submit the form
                    form.submit();
                }
            }
        });
    </script>
@endsection
