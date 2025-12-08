@extends('core::layouts.master')
@section('title')
    {{ $client->first_name }} {{ $client->last_name }}
@endsection
@section('styles')
<style>
/* Ultra-Modern Banking Interface */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
}

body {
    background: #f8f9fa;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Hero Section */
.client-hero-modern {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.client-hero-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.hero-content {
    position: relative;
    z-index: 1;
}

.profile-section {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 30px;
}

.profile-photo-ultra {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid rgba(255,255,255,0.3);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    object-fit: cover;
    background: white;
}

.profile-info h1 {
    color: white;
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 10px 0;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.profile-meta {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.client-id-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 8px 16px;
    border-radius: 20px;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.status-badge-ultra {
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.status-pending { background: #f39c12; color: white; }
.status-active { background: #27ae60; color: white; }
.status-rejected { background: #e74c3c; color: white; }
.status-inactive { background: #95a5a6; color: white; }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card-ultra {
    background: white;
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.stat-card-ultra::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stat-card-ultra:hover::before {
    transform: scaleX(1);
}

.stat-card-ultra:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.stat-icon-ultra {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--primary-gradient);
    color: white;
    font-size: 24px;
}

.stat-value-ultra {
    font-size: 36px;
    font-weight: 800;
    color: #2c3e50;
    margin: 10px 0;
    line-height: 1;
}

.stat-label-ultra {
    font-size: 13px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
}

/* Alert Cards */
.alert-card-ultra {
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: none;
}

.alert-pending {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border-left: 5px solid #f39c12;
}

.alert-approved {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-left: 5px solid #27ae60;
}

.alert-rejected {
    background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
    border-left: 5px solid #e74c3c;
}

.alert-icon-ultra {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 24px;
    flex-shrink: 0;
}

/* Content Cards */
.content-card-ultra {
    background: white;
    border-radius: 16px;
    padding: 0;
    margin-bottom: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header-ultra {
    padding: 25px 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #e9ecef;
}

.card-header-ultra h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body-ultra {
    padding: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 15px 0;
    border-bottom: 1px solid #ecf0f1;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 5px;
}

.info-value {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 600;
}

/* Modern Buttons */
.btn-ultra {
    border: none;
    border-radius: 30px;
    padding: 14px 32px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 13px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.btn-ultra::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-ultra:hover::before {
    width: 300px;
    height: 300px;
}

.btn-ultra:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.btn-ultra-primary {
    background: var(--primary-gradient);
    color: white;
}

.btn-ultra-success {
    background: var(--success-gradient);
    color: white;
}

.btn-ultra-danger {
    background: var(--danger-gradient);
    color: white;
}

.btn-ultra-warning {
    background: var(--warning-gradient);
    color: white;
}

/* Tabs */
.nav-tabs-ultra {
    border: none;
    background: white;
    border-radius: 16px;
    padding: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.nav-tabs-ultra .nav-link {
    border: none;
    border-radius: 12px;
    color: #7f8c8d;
    font-weight: 600;
    padding: 12px 24px;
    transition: all 0.3s ease;
    background: transparent;
}

.nav-tabs-ultra .nav-link:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.nav-tabs-ultra .nav-link.active {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Action Buttons Group */
.action-buttons-ultra {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-section {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-info h1 {
        font-size: 24px;
    }
    
    .stat-value-ultra {
        font-size: 28px;
    }
}

/* Loading Animation */
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

.skeleton {
    animation: shimmer 2s infinite;
    background: linear-gradient(to right, #f0f0f0 8%, #f8f8f8 18%, #f0f0f0 33%);
    background-size: 1000px 100%;
}
</style>
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <a href="#" onclick="window.history.back()" class="btn btn-ultra-primary" style="border-radius: 12px; padding: 10px 20px;">
                    <i class="fas fa-arrow-left"></i> {{ trans_choice('core::general.back',1) }}
                </a>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{url('client')}}">{{ trans_choice('client::general.client',2) }}</a></li>
                    <li class="breadcrumb-item active">{{ $client->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" id="app">
    <!-- Hero Section -->
    <div class="client-hero-modern">
        <div class="hero-content">
            <div class="profile-section">
                <div>
                    @if(!empty($client->photo))
                        <img class="profile-photo-ultra" src="{{asset('storage/uploads/clients/'.$client->photo)}}" alt="{{ $client->name }}">
                    @else
                        <img class="profile-photo-ultra" src="{{asset('themes/adminlte/img/user.png')}}" alt="{{ $client->name }}">
                    @endif
                </div>
                <div class="profile-info flex-grow-1">
                    <h1>
                        @if(!empty($client->title)){{$client->title->name}}@endif
                        {{ $client->name }}
                    </h1>
                    <div class="profile-meta">
                        <span class="client-id-badge">
                            <i class="fas fa-id-card"></i> ID: {{ $client->id }}
                        </span>
                        @if($client->status=='pending')
                            <span class="status-badge-ultra status-pending">Pending</span>
                        @elseif($client->status=='active')
                            <span class="status-badge-ultra status-active">Active</span>
                        @elseif($client->status=='rejected')
                            <span class="status-badge-ultra status-rejected">Rejected</span>
                        @else
                            <span class="status-badge-ultra status-inactive">{{ ucfirst($client->status) }}</span>
                        @endif
                        @if(!empty($client->profession))
                            <span class="client-id-badge">
                                <i class="fas fa-briefcase"></i> {{ $client->profession->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons in Hero -->
            <div class="action-buttons-ultra">
                @if($client->status == 'pending')
                    @can('client.clients.activate')
                        <button class="btn-ultra btn-ultra-success" data-toggle="modal" data-target="#approve_client_modal">
                            <i class="fas fa-check-circle"></i> Approve Client
                        </button>
                        <button class="btn-ultra btn-ultra-danger" data-toggle="modal" data-target="#reject_client_modal">
                            <i class="fas fa-times-circle"></i> Reject Client
                        </button>
                    @endcan
                @endif
                
                @if($client->status == 'active')
                    @can('client.clients.activate')
                        <a href="{{url('client/' . $client->id . '/undo_approval')}}" class="btn-ultra btn-ultra-warning confirm">
                            <i class="fas fa-undo"></i> Undo Approval
                        </a>
                    @endcan
                @endif
                
                @can('client.clients.edit')
                    <a href="{{url('client/' . $client->id . '/edit')}}" class="btn-ultra btn-ultra-primary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <button class="btn-ultra btn-ultra-primary" data-toggle="modal" data-target="#transfer_client_modal">
                        <i class="fas fa-exchange-alt"></i> Transfer
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card-ultra">
            <div class="stat-icon-ultra">
                <i class="fas fa-piggy-bank"></i>
            </div>
            <div class="stat-value-ultra">
                @php
                    $total_savings = \Modules\Savings\Entities\Savings::where('client_id', $client->id)->sum('balance_derived');
                @endphp
                GH₵ {{ number_format($total_savings, 2) }}
            </div>
            <div class="stat-label-ultra">Total Savings</div>
        </div>

        <div class="stat-card-ultra">
            <div class="stat-icon-ultra" style="background: var(--success-gradient);">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-value-ultra">
                @php
                    $active_loans = \Modules\Loan\Entities\Loan::where('client_id', $client->id)->whereIn('status', ['active', 'disbursed'])->count();
                @endphp
                {{ $active_loans }}
            </div>
            <div class="stat-label-ultra">Active Loans</div>
        </div>

        <div class="stat-card-ultra">
            <div class="stat-icon-ultra" style="background: var(--info-gradient);">
                <i class="fas fa-university"></i>
            </div>
            <div class="stat-value-ultra">
                @php
                    $total_accounts = \Modules\Savings\Entities\Savings::where('client_id', $client->id)->count();
                @endphp
                {{ $total_accounts }}
            </div>
            <div class="stat-label-ultra">Total Accounts</div>
        </div>

        <div class="stat-card-ultra">
            <div class="stat-icon-ultra" style="background: var(--dark-gradient);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-value-ultra">
                {{ \Carbon\Carbon::parse($client->created_date)->format('M Y') }}
            </div>
            <div class="stat-label-ultra">Member Since</div>
        </div>
    </div>

    <!-- Status Alerts -->
    @if($client->status == 'pending')
    <div class="alert-card-ultra alert-pending">
        <div class="alert-icon-ultra" style="background: #f39c12; color: white;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="flex-grow-1">
            <h5 style="color: #f39c12; font-weight: 700; margin: 0 0 5px 0;">Pending Approval</h5>
            <p style="color: #7f8c8d; margin: 0;">This client is awaiting approval. Review the information and take action.</p>
        </div>
    </div>
    @endif

    @if($client->status == 'active' && $client->approved_on_date)
    <div class="alert-card-ultra alert-approved">
        <div class="alert-icon-ultra" style="background: #27ae60; color: white;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="flex-grow-1">
            <h5 style="color: #27ae60; font-weight: 700; margin: 0 0 5px 0;">Approved Client</h5>
            <p style="color: #7f8c8d; margin: 0;">
                Approved by <strong>{{ $client->approved_by_user->first_name ?? 'System' }} {{ $client->approved_by_user->last_name ?? '' }}</strong> 
                on <strong>{{ \Carbon\Carbon::parse($client->approved_on_date)->format('d M Y') }}</strong>
                @if($client->approved_notes)
                    <br><em>"{{ $client->approved_notes }}"</em>
                @endif
            </p>
        </div>
    </div>
    @endif

    @if($client->status == 'rejected')
    <div class="alert-card-ultra alert-rejected">
        <div class="alert-icon-ultra" style="background: #e74c3c; color: white;">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="flex-grow-1">
            <h5 style="color: #e74c3c; font-weight: 700; margin: 0 0 5px 0;">Rejected Client</h5>
            <p style="color: #7f8c8d; margin: 0;">
                Rejected by <strong>{{ $client->rejected_by_user->first_name ?? 'System' }} {{ $client->rejected_by_user->last_name ?? '' }}</strong> 
                on <strong>{{ \Carbon\Carbon::parse($client->rejected_on_date)->format('d M Y') }}</strong>
                @if($client->rejected_notes)
                    <br><strong>Reason:</strong> <em>"{{ $client->rejected_notes }}"</em>
                @endif
            </p>
        </div>
        @can('client.clients.activate')
        <a href="{{url('client/' . $client->id . '/undo_rejection')}}" class="btn-ultra btn-ultra-primary confirm">
            <i class="fas fa-undo"></i> Undo
        </a>
        @endcan
    </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- Personal Information -->
            <div class="content-card-ultra">
                <div class="card-header-ultra">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                </div>
                <div class="card-body-ultra">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $client->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">External ID</div>
                        <div class="info-value">{{ $client->external_id ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">{{ $client->dob ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value">{{ ucfirst($client->gender) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Marital Status</div>
                        <div class="info-value">{{ ucfirst($client->marital_status ?? 'N/A') }}</div>
                    </div>
                    @if(!empty($client->client_type))
                    <div class="info-item">
                        <div class="info-label">Client Type</div>
                        <div class="info-value">{{ $client->client_type->name }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="content-card-ultra">
                <div class="card-header-ultra">
                    <h3><i class="fas fa-phone"></i> Contact Information</h3>
                </div>
                <div class="card-body-ultra">
                    <div class="info-item">
                        <div class="info-label">Mobile</div>
                        <div class="info-value">{{ $client->mobile ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $client->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $client->address ?? 'N/A' }}</div>
                    </div>
                    @if(!empty($client->country))
                    <div class="info-item">
                        <div class="info-label">Country</div>
                        <div class="info-value">{{ $client->country->name }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">Zip Code</div>
                        <div class="info-value">{{ $client->zip ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Branch & Staff -->
            <div class="content-card-ultra">
                <div class="card-header-ultra">
                    <h3><i class="fas fa-building"></i> Branch & Staff</h3>
                </div>
                <div class="card-body-ultra">
                    @if(!empty($client->branch))
                    <div class="info-item">
                        <div class="info-label">Branch</div>
                        <div class="info-value">{{ $client->branch->name }}</div>
                    </div>
                    @endif
                    @if(!empty($client->loan_officer))
                    <div class="info-item">
                        <div class="info-label">Loan Officer</div>
                        <div class="info-value">{{ $client->loan_officer->first_name }} {{ $client->loan_officer->last_name }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">Joined Date</div>
                        <div class="info-value">{{ $client->created_date }}</div>
                    </div>
                    @if($client->activation_date)
                    <div class="info-item">
                        <div class="info-label">Activation Date</div>
                        <div class="info-value">{{ $client->activation_date }}</div>
                    </div>
                    @endif
                </div>
            </div>

            @if($client->notes)
            <!-- Notes -->
            <div class="content-card-ultra">
                <div class="card-header-ultra">
                    <h3><i class="fas fa-sticky-note"></i> Notes</h3>
                </div>
                <div class="card-body-ultra">
                    <p style="color: #7f8c8d; margin: 0;">{{ $client->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Tabs -->
            <ul class="nav nav-tabs-ultra">
                <li class="nav-item">
                    <a class="nav-link active" href="#accounts" data-toggle="tab">
                        <i class="fas fa-university"></i> Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#loans" data-toggle="tab">
                        <i class="fas fa-money-bill-wave"></i> Loans
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#identifications" data-toggle="tab">
                        <i class="fas fa-id-card"></i> IDs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#next_of_kin" data-toggle="tab">
                        <i class="fas fa-users"></i> Next of Kin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#files" data-toggle="tab">
                        <i class="fas fa-folder"></i> Files
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Accounts Tab -->
                <div class="tab-pane fade show active" id="accounts">
                    <div class="content-card-ultra">
                        <div class="card-header-ultra">
                            <h3><i class="fas fa-piggy-bank"></i> Savings Accounts</h3>
                        </div>
                        <div class="card-body-ultra">
                            <table id="savings-data-table" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans_choice('core::general.id',1) }}</th>
                                    <th>{{ trans_choice('savings::general.interest_rate',1) }}</th>
                                    <th>{{ trans_choice('savings::general.balance',1) }}</th>
                                    <th>{{ trans_choice('core::general.status',1) }}</th>
                                    <th>{{ trans_choice('savings::general.product',1) }}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Loans Tab -->
                <div class="tab-pane fade" id="loans">
                    <div class="content-card-ultra">
                        <div class="card-header-ultra">
                            <h3><i class="fas fa-hand-holding-usd"></i> Loans</h3>
                        </div>
                        <div class="card-body-ultra">
                            <table id="loan-data-table" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans_choice('core::general.id',1) }}</th>
                                    <th>{{ trans_choice('loan::general.principal',1) }}</th>
                                    <th>{{ trans_choice('loan::general.balance',1) }}</th>
                                    <th>{{ trans_choice('loan::general.disbursed_on_date',1) }}</th>
                                    <th>{{ trans_choice('core::general.status',1) }}</th>
                                    <th>{{ trans_choice('loan::general.product',1) }}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Other tabs remain the same structure -->
                <div class="tab-pane fade" id="identifications">
                    <div class="content-card-ultra">
                        <div class="card-header-ultra">
                            <h3><i class="fas fa-id-card"></i> Identification Documents</h3>
                        </div>
                        <div class="card-body-ultra">
                            @can('client.clients.identification.create')
                                <a href="{{url('client/'.$client->id.'/client_identification/create')}}" class="btn-ultra btn-ultra-primary mb-3">
                                    <i class="fas fa-plus"></i> Add Identification
                                </a>
                            @endcan
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans_choice('client::general.identification_type',1) }}</th>
                                    <th>{{ trans_choice('core::general.id',1) }}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($client->identifications as $identification)
                                    <tr>
                                        <td>{{ $identification->client_identification_type->name }}</td>
                                        <td>{{ $identification->identification_value }}</td>
                                        <td>
                                            <a href="{{url('client/client_identification/'.$identification->id.'/edit')}}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="next_of_kin">
                    <div class="content-card-ultra">
                        <div class="card-header-ultra">
                            <h3><i class="fas fa-users"></i> Next of Kin</h3>
                        </div>
                        <div class="card-body-ultra">
                            @can('client.clients.next_of_kin.create')
                                <a href="{{url('client/'.$client->id.'/client_next_of_kin/create')}}" class="btn-ultra btn-ultra-primary mb-3">
                                    <i class="fas fa-plus"></i> Add Next of Kin
                                </a>
                            @endcan
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans_choice('core::general.name',1) }}</th>
                                    <th>{{ trans_choice('client::general.relationship',1) }}</th>
                                    <th>{{ trans_choice('core::general.mobile',1) }}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($client->next_of_kins as $next_of_kin)
                                    <tr>
                                        <td>{{ $next_of_kin->first_name }} {{ $next_of_kin->last_name }}</td>
                                        <td>{{ $next_of_kin->client_relationship->name ?? '' }}</td>
                                        <td>{{ $next_of_kin->mobile }}</td>
                                        <td>
                                            <a href="{{url('client/client_next_of_kin/'.$next_of_kin->id.'/edit')}}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="files">
                    <div class="content-card-ultra">
                        <div class="card-header-ultra">
                            <h3><i class="fas fa-folder"></i> Files & Documents</h3>
                        </div>
                        <div class="card-body-ultra">
                            @can('client.clients.files.create')
                                <a href="{{url('client/'.$client->id.'/file/create')}}" class="btn-ultra btn-ultra-primary mb-3">
                                    <i class="fas fa-upload"></i> Upload File
                                </a>
                            @endcan
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans_choice('core::general.name',1) }}</th>
                                    <th>{{ trans_choice('core::general.description',1) }}</th>
                                    <th>{{ trans_choice('core::general.action',1) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($client->files as $file)
                                    <tr>
                                        <td>{{ $file->name }}</td>
                                        <td>{{ $file->description }}</td>
                                        <td>
                                            <a href="{{asset('storage/uploads/clients/'.$file->file_name)}}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="approve_client_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #ecf0f1;">
                    <h5 class="modal-title" style="font-weight: 700;"><i class="fas fa-check-circle text-success"></i> Approve Client</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{url('client/' . $client->id . '/approve')}}">
                    @csrf
                    <div class="modal-body" style="padding: 30px;">
                        <p style="font-size: 16px; color: #7f8c8d;">Are you sure you want to approve <strong>{{ $client->name }}</strong>?</p>
                        <div class="form-group">
                            <label for="approved_on_date" style="font-weight: 600;">Approval Date <span class="text-danger">*</span></label>
                            <input type="date" name="approved_on_date" id="approved_on_date" class="form-control" style="border-radius: 8px; padding: 12px;" value="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="approved_notes" style="font-weight: 600;">Notes (Optional)</label>
                            <textarea name="approved_notes" id="approved_notes" class="form-control" style="border-radius: 8px; padding: 12px;" rows="3" placeholder="Enter any approval notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #ecf0f1; padding: 20px 30px;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="btn-ultra btn-ultra-success">
                            <i class="fas fa-check"></i> Approve Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reject_client_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #ecf0f1;">
                    <h5 class="modal-title" style="font-weight: 700;"><i class="fas fa-times-circle text-danger"></i> Reject Client</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{url('client/' . $client->id . '/reject')}}">
                    @csrf
                    <div class="modal-body" style="padding: 30px;">
                        <p style="font-size: 16px; color: #7f8c8d;">Are you sure you want to reject <strong>{{ $client->name }}</strong>?</p>
                        <div class="form-group">
                            <label for="rejected_notes" style="font-weight: 600;">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejected_notes" id="rejected_notes" class="form-control" style="border-radius: 8px; padding: 12px;" rows="4" placeholder="Enter reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #ecf0f1; padding: 20px 30px;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="btn-ultra btn-ultra-danger">
                            <i class="fas fa-times"></i> Reject Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transfer Modal (existing) -->
    <div class="modal fade" id="transfer_client_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Client</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="post" action="{{url('client/'.$client->id.'/transfer')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="branch_id">Branch</label>
                            <select class="form-control select2" name="branch_id" id="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="loan_officer_id">Loan Officer</label>
                            <select class="form-control select2" name="loan_officer_id" id="loan_officer_id" required>
                                <option value="">Select Officer</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-ultra btn-ultra-primary">Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.confirm').on('click', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'This action will change the client status',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then(function() {
                window.location = href;
            })
        });

        $('#savings-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('savings/get_savings?client_id='.$client->id) !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'interest_rate', name: 'interest_rate'},
                {data: 'balance', name: 'balance'},
                {data: 'status', name: 'status'},
                {data: 'savings_product', name: 'savings_products.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "order": [[0, "desc"]],
            responsive: true,
            "autoWidth": false
        });

        $('#loan-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('loan/get_loans?client_id='.$client->id) !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'principal', name: 'principal'},
                {data: 'balance', name: 'balance', orderable: false},
                {data: 'disbursed_on_date', name: 'disbursed_on_date'},
                {data: 'status', name: 'status'},
                {data: 'loan_product', name: 'loan_products.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "order": [[0, "desc"]],
            responsive: true
        });
    });
</script>
@endsection