@extends('core::layouts.master')
@section('title')
    {{ trans_choice('savings::general.savings',1) }} {{ trans_choice('core::general.detail',2) }}
@endsection
@section('styles')
<style>
    /* Modern Banking Styles */
    .account-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 40px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        margin-bottom: 30px;
    }
    
    .account-hero h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .account-hero .account-number {
        font-size: 20px;
        opacity: 0.95;
        font-weight: 500;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .stat-card .stat-label {
        font-size: 13px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
    }
    
    .info-card h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1f2937;
        display: flex;
        align-items: center;
    }
    
    .info-card h5 i {
        margin-right: 10px;
        color: #667eea;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .info-value {
        color: #1f2937;
        font-weight: 600;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-submitted { background: #fef3c7; color: #92400e; }
    .status-approved { background: #dbeafe; color: #1e40af; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-closed { background: #fee2e2; color: #991b1b; }
    .status-rejected { background: #fecaca; color: #7f1d1d; }
    .status-withdrawn { background: #fecaca; color: #7f1d1d; }
    .status-dormant { background: #fef3c7; color: #92400e; }
    .status-inactive { background: #f3f4f6; color: #4b5563; }
    
    .action-btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 30px;
    }
    
    .action-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-primary-modern {
        background: #667eea;
        color: white;
    }
    
    .btn-success-modern {
        background: #059669;
        color: white;
    }
    
    .btn-warning-modern {
        background: #d97706;
        color: white;
    }
    
    .btn-danger-modern {
        background: #dc2626;
        color: white;
    }
    
    .transaction-timeline {
        position: relative;
    }
    
    .transaction-item {
        display: flex;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    
    .transaction-item:hover {
        background: #f9fafb;
    }
    
    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        font-size: 18px;
    }
    
    .transaction-icon.deposit {
        background: #d1fae5;
        color: #065f46;
    }
    
    .transaction-icon.withdrawal {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .transaction-icon.interest {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .transaction-details {
        flex: 1;
    }
    
    .transaction-amount {
        font-weight: 700;
        font-size: 16px;
    }
    
    .transaction-amount.positive {
        color: #059669;
    }
    
    .transaction-amount.negative {
        color: #dc2626;
    }
    
    .modern-modal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .modern-modal .modal-header {
        background: #f9fafb;
        border-radius: 12px 12px 0 0;
        border-bottom: 1px solid #e5e7eb;
        padding: 20px 24px;
    }
    
    .modern-modal .modal-title {
        font-weight: 600;
        color: #1f2937;
    }
    
    .modern-modal .modal-body {
        padding: 24px;
    }
    
    .modern-modal .modal-footer {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 12px 12px;
        padding: 16px 24px;
    }
    
    .nav-tabs-modern {
        border-bottom: 2px solid #e5e7eb;
    }
    
    .nav-tabs-modern .nav-link {
        border: none;
        color: #6b7280;
        font-weight: 600;
        padding: 12px 24px;
        transition: all 0.3s;
    }
    
    .nav-tabs-modern .nav-link:hover {
        color: #667eea;
        background: transparent;
    }
    
    .nav-tabs-modern .nav-link.active {
        color: #667eea;
        background: transparent;
        border-bottom: 3px solid #667eea;
    }
    
    @media (max-width: 768px) {
        .account-hero {
            padding: 24px;
        }
        
        .stat-card {
            margin-bottom: 12px;
        }
        
        .action-btn-group {
            flex-direction: column;
        }
        
        .action-btn {
            width: 100%;
        }
    }
</style>
@stop

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('savings::general.savings',1) }} {{ trans_choice('core::general.detail',2) }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings')}}">{{ trans_choice('savings::general.savings',2) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('savings::general.savings',1) }} {{ trans_choice('core::general.detail',2) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <!-- Hero Account Card -->
        <div class="account-hero">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i class="fas fa-piggy-bank"></i> {{$savings->savings_product->name}}</h2>
                    <div class="account-number">
                        <i class="fas fa-hashtag"></i> {{$savings->account_number}}
                        @if($savings->status == 'submitted' || $savings->status == 'pending')
                            <span class="status-badge status-submitted ml-3">{{ trans_choice('savings::general.pending_approval',1) }}</span>
                        @endif
                        @if($savings->status == 'approved')
                            <span class="status-badge status-approved ml-3">{{ trans_choice('savings::general.awaiting_activation',1) }}</span>
                        @endif
                        @if($savings->status == 'active')
                            <span class="status-badge status-active ml-3">{{ trans_choice('savings::general.active',1) }}</span>
                        @endif
                        @if($savings->status == 'closed')
                            <span class="status-badge status-closed ml-3">{{ trans_choice('savings::general.closed',1) }}</span>
                        @endif
                        @if($savings->status == 'rejected')
                            <span class="status-badge status-rejected ml-3">{{ trans_choice('savings::general.rejected',1) }}</span>
                        @endif
                        @if($savings->status == 'withdrawn')
                            <span class="status-badge status-withdrawn ml-3">{{ trans_choice('savings::general.withdrawn',1) }}</span>
                        @endif
                        @if($savings->status == 'dormant')
                            <span class="status-badge status-dormant ml-3">{{ trans_choice('savings::general.dormant',1) }}</span>
                        @endif
                        @if($savings->status == 'inactive')
                            <span class="status-badge status-inactive ml-3">{{ trans_choice('savings::general.inactive',1) }}</span>
                        @endif
                    </div>
                    
                    @if($savings->status=='active' ||$savings->status=='closed'||$savings->status=='dormant'||$savings->status=='inactive')
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-label">{{ trans_choice('savings::general.current',1) }} {{ trans_choice('savings::general.balance',1) }}</div>
                                <div class="stat-value">
                                    {{number_format($savings->transactions->where('reversed',0)->sum('credit')-$savings->transactions->where('reversed',0)->sum('debit'),$savings->decimals)}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-label">{{ trans_choice('savings::general.interest',1) }} {{ trans_choice('savings::general.earned',1) }}</div>
                                <div class="stat-value">
                                    {{number_format($savings->transactions->where('reversed',0)->where('savings_transaction_type_id',11)->sum('amount')+$savings->calculated_interest,$savings->decimals)}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-label">{{ trans_choice('core::general.total',1) }} {{ trans_choice('savings::general.deposit',2) }}</div>
                                <div class="stat-value">
                                    {{number_format($savings->transactions->where('reversed',0)->where('savings_transaction_type_id',1)->sum('amount'),$savings->decimals)}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <!-- Action Buttons -->
                    <div class="action-btn-group">
                        @if($savings->status=='submitted' ||$savings->status=='pending')
                            @can('savings.savings.approve_savings')
                                <a href="#" data-toggle="modal" data-target="#approve_savings_modal" class="btn action-btn btn-success-modern">
                                    <i class="fas fa-check"></i> {{ trans_choice('savings::general.approve',1) }}
                                </a>
                                <a href="#" data-toggle="modal" data-target="#reject_savings_modal" class="btn action-btn btn-danger-modern">
                                    <i class="fas fa-times"></i> {{ trans_choice('savings::general.reject',1) }}
                                </a>
                                <a href="#" data-toggle="modal" data-target="#withdraw_savings_modal" class="btn action-btn btn-warning-modern">
                                    <i class="fas fa-undo"></i> {{ trans_choice('savings::general.withdraw',1) }}
                                </a>
                            @endcan
                            @can('savings.savings.edit')
                                <a href="{{url('savings/'.$savings->id.'/edit')}}" class="btn action-btn btn-primary-modern">
                                    <i class="fas fa-edit"></i> {{ trans_choice('core::general.edit',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='active')
                            <a href="{{url('savings/'.$savings->id.'/statement')}}" class="btn action-btn btn-primary-modern">
                                <i class="fas fa-file-invoice"></i> View Statement
                            </a>
                            @can('savings.savings.transactions.create')
                                <a href="{{url('savings/'.$savings->id.'/deposit/create')}}" class="btn action-btn btn-success-modern">
                                    <i class="fas fa-dollar-sign"></i> {{ trans_choice('savings::general.make',1) }} {{ trans_choice('savings::general.deposit',1) }}
                                </a>
                                <a href="{{url('savings/'.$savings->id.'/withdrawal/create')}}" class="btn action-btn btn-warning-modern">
                                    <i class="fas fa-money-bill"></i> {{ trans_choice('savings::general.make',1) }} {{ trans_choice('savings::general.withdrawal',1) }}
                                </a>
                            @endcan
                            @can('savings.savings.edit')
                                <a href="#" data-toggle="modal" data-target="#change_savings_officer_modal" class="btn action-btn btn-primary-modern">
                                    <i class="fas fa-user-edit"></i> {{ trans_choice('savings::general.change',1) }} {{ trans_choice('savings::general.officer',1) }}
                                </a>
                            @endcan
                            @can('savings.savings.charges.create')
                                <a href="#" data-toggle="modal" data-target="#add_charge_modal" class="btn action-btn btn-primary-modern">
                                    <i class="fa fa-plus"></i> {{ trans_choice('core::general.add',1) }} {{ trans_choice('savings::general.charge',1) }}
                                </a>
                            @endcan
                            @can('savings.savings.close_savings')
                                <a href="#" data-toggle="modal" data-target="#close_savings_modal" class="btn action-btn btn-danger-modern">
                                    <i class="fas fa-lock"></i> {{ trans_choice('core::general.close',1) }} {{ trans_choice('savings::general.savings',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='approved')
                            @can('savings.savings.activate_savings')
                                <a href="#" data-toggle="modal" data-target="#activate_savings_modal" class="btn action-btn btn-success-modern">
                                    <i class="fa fa-flag"></i> {{ trans_choice('savings::general.activate',1) }}
                                </a>
                            @endcan
                            @can('savings.savings.approve_savings')
                                <a href="{{url('savings/'.$savings->id.'/undo_approval')}}" class="btn action-btn btn-warning-modern confirm">
                                    <i class="fas fa-undo"></i> {{ trans_choice('savings::general.undo',1) }} {{ trans_choice('savings::general.approval',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='closed')
                            @can('savings.savings.close_savings')
                                <a href="{{url('savings/'.$savings->id.'/undo_closed')}}" class="btn action-btn btn-success-modern confirm">
                                    <i class="fas fa-unlock"></i> {{ trans_choice('savings::general.activate',1) }} {{ trans_choice('savings::general.savings',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='rejected')
                            @can('savings.savings.approve_savings')
                                <a href="{{url('savings/'.$savings->id.'/undo_rejection')}}" class="btn action-btn btn-primary-modern confirm">
                                    <i class="fas fa-undo"></i> {{ trans_choice('savings::general.undo',1) }} {{ trans_choice('savings::general.rejection',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='withdrawn')
                            @can('savings.savings.approve_savings')
                                <a href="{{url('savings/'.$savings->id.'/undo_withdrawn')}}" class="btn action-btn btn-primary-modern confirm">
                                    <i class="fas fa-undo"></i> {{ trans_choice('savings::general.undo',1) }} {{ trans_choice('savings::general.withdrawn',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='inactive')
                            @can('savings.savings.inactive_savings')
                                <a href="{{url('savings/'.$savings->id.'/undo_inactive')}}" class="btn action-btn btn-success-modern confirm">
                                    <i class="fa fa-check"></i> {{ trans_choice('savings::general.activate',1) }} {{ trans_choice('savings::general.savings',1) }}
                                </a>
                            @endcan
                        @endif
                        
                        @if($savings->status=='dormant')
                            @can('savings.savings.dormant_savings')
                                <a href="{{url('savings/'.$savings->id.'/undo_dormant')}}" class="btn action-btn btn-success-modern confirm">
                                    <i class="fa fa-check"></i> {{ trans_choice('savings::general.activate',1) }} {{ trans_choice('savings::general.savings',1) }}
                                </a>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Client Information -->
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="fas fa-user"></i> {{ trans_choice('client::general.client',1) }} {{ trans_choice('core::general.information',1) }}</h5>
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('core::general.name',1) }}</span>
                        <span class="info-value">
                            @if(!empty($savings->client))
                                <a href="{{url('client/'.$savings->client_id.'/show')}}">{{$savings->client->first_name}} {{$savings->client->middle_name}} {{$savings->client->last_name}}</a>
                            @endif
                        </span>
                    </div>
                    @if(!empty($savings->client->mobile))
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('client::general.mobile',1) }}</span>
                        <span class="info-value">{{$savings->client->mobile}}</span>
                    </div>
                    @endif
                    @if(!empty($savings->client->branch))
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('core::general.branch',1) }}</span>
                        <span class="info-value">{{$savings->client->branch->name}}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('savings::general.savings',1) }} {{ trans_choice('savings::general.officer',1) }}</span>
                        <span class="info-value">
                            @if(!empty($savings->savings_officer))
                                {{$savings->savings_officer->first_name}} {{$savings->savings_officer->last_name}}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="fas fa-info-circle"></i> {{ trans_choice('savings::general.account',1) }} {{ trans_choice('core::general.information',1) }}</h5>
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('savings::general.account_number',1) }}</span>
                        <span class="info-value">{{$savings->account_number}}</span>
                    </div>
                    @if($savings->external_id)
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('savings::general.external_id',1) }}</span>
                        <span class="info-value">{{$savings->external_id}}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('core::general.currency',1) }}</span>
                        <span class="info-value">
                            @if(!empty($savings->currency))
                                {{$savings->currency->name}}
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('savings::general.interest_rate',1) }}</span>
                        <span class="info-value">{{number_format($savings->interest_rate,2)}}% p.a.</span>
                    </div>
                    @if($savings->status=='active' ||$savings->status=='closed'||$savings->status=='dormant'||$savings->status=='inactive')
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('savings::general.activated_on',1) }}</span>
                        <span class="info-value">{{$savings->activated_on_date}}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">{{ trans_choice('savings::general.submitted_on',1) }}</span>
                        <span class="info-value">{{$savings->submitted_on_date}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs nav-tabs-modern">
                            <li class="nav-item">
                                <a href="#account_details" class="nav-link active" data-toggle="tab">
                                    <i class="fas fa-info-circle"></i> {{ trans_choice('savings::general.account',1) }} {{ trans_choice('core::general.detail',2) }}
                                </a>
                            </li>
                            @if($savings->status=='active' ||$savings->status=='closed'||$savings->status=='dormant'||$savings->status=='overpaid'||$savings->status=='rescheduled')
                                @can('savings.savings.transactions.index')
                                    <li class="nav-item">
                                        <a href="#savings_transactions" class="nav-link" data-toggle="tab">
                                            <i class="fas fa-exchange-alt"></i> {{ trans_choice('savings::general.transaction',2) }}
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @can('savings.savings.charges.index')
                                <li class="nav-item">
                                    <a href="#savings_charges" class="nav-link" data-toggle="tab">
                                        <i class="fas fa-file-invoice-dollar"></i> {{ trans_choice('savings::general.charge',2) }}
                                    </a>
                                </li>
                            @endcan
                            @can('savings.savings.files.index')
                                <li class="nav-item">
                                    <a href="#savings_files" class="nav-link" data-toggle="tab">
                                        <i class="fas fa-folder-open"></i> {{ trans_choice('savings::general.file',2) }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Account Details Tab -->
                            <div class="tab-pane active" id="account_details">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tbody>
                                        <tr>
                                            <td><strong>{{trans_choice('savings::general.compounding_period',1)}}</strong></td>
                                            <td>
                                                @if($savings->compounding_period=='daily')
                                                    {{trans_choice('savings::general.daily',2)}}
                                                @endif
                                                @if($savings->compounding_period=='weekly')
                                                    {{trans_choice('savings::general.weekly',2)}}
                                                @endif
                                                @if($savings->compounding_period=='monthly')
                                                    {{trans_choice('savings::general.monthly',2)}}
                                                @endif
                                                @if($savings->compounding_period=='biannual')
                                                    {{trans_choice('savings::general.biannual',2)}}
                                                @endif
                                                @if($savings->compounding_period=='annually')
                                                    {{trans_choice('savings::general.annually',2)}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{trans_choice('savings::general.interest_posting_period_type',1)}}</strong></td>
                                            <td>
                                                @if($savings->interest_posting_period_type=='daily')
                                                    {{trans_choice('savings::general.daily',2)}}
                                                @endif
                                                @if($savings->interest_posting_period_type=='weekly')
                                                    {{trans_choice('savings::general.weekly',2)}}
                                                @endif
                                                @if($savings->interest_posting_period_type=='monthly')
                                                    {{trans_choice('savings::general.monthly',2)}}
                                                @endif
                                                @if($savings->interest_posting_period_type=='biannual')
                                                    {{trans_choice('savings::general.biannual',2)}}
                                                @endif
                                                @if($savings->interest_posting_period_type=='annually')
                                                    {{trans_choice('savings::general.annually',2)}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{trans_choice('savings::general.interest_calculation_type',1)}}</strong></td>
                                            <td>
                                                @if($savings->interest_calculation_type=='daily_balance')
                                                    {{trans_choice('savings::general.daily_balance',1)}}
                                                @endif
                                                @if($savings->interest_calculation_type=='average_daily_balance')
                                                    {{trans_choice('savings::general.average_balance',1)}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{trans_choice('savings::general.lockin_period',1)}}</strong></td>
                                            <td>
                                                {{$savings->lockin_period}}
                                                @if($savings->lockin_type=='days')
                                                    {{trans_choice('savings::general.day',2)}}
                                                @endif
                                                @if($savings->lockin_type=='weeks')
                                                    {{trans_choice('savings::general.week',2)}}
                                                @endif
                                                @if($savings->lockin_type=='months')
                                                    {{trans_choice('savings::general.month',2)}}
                                                @endif
                                                @if($savings->lockin_type=='years')
                                                    {{trans_choice('savings::general.year',2)}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{trans_choice('savings::general.allow_overdraft',1)}}</strong></td>
                                            <td>
                                                @if($savings->allow_overdraft==1)
                                                    {{trans_choice('core::general.yes',1)}}
                                                @else
                                                    {{trans_choice('core::general.no',1)}}
                                                @endif
                                            </td>
                                        </tr>
                                        @if($savings->allow_overdraft==1)
                                            <tr>
                                                <td><strong>{{trans_choice('savings::general.overdraft_limit',1)}}</strong></td>
                                                <td>
                                                    {{number_format($savings->overdraft_limit,$savings->decimals)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{trans_choice('savings::general.overdraft_interest_rate',1)}}</strong></td>
                                                <td>
                                                    {{number_format($savings->overdraft_interest_rate,2)}}%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{trans_choice('savings::general.minimum_overdraft_for_interest',1)}}</strong></td>
                                                <td>
                                                    {{number_format($savings->minimum_overdraft_for_interest,$savings->decimals)}}
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Transactions Tab -->
                            @if($savings->status=='active' ||$savings->status=='closed'||$savings->status=='dormant'||$savings->status=='overpaid'||$savings->status=='rescheduled')
                                @can('savings.savings.transactions.index')
                                    <div class="tab-pane" id="savings_transactions">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>{{trans_choice('core::general.date',1)}}</th>
                                                    <th>{{trans_choice('core::general.submitted_on',1)}}</th>
                                                    <th>{{trans_choice('savings::general.transaction',1)}} {{trans_choice('core::general.type',1)}}</th>
                                                    <th>{{trans_choice('savings::general.transaction',1)}} {{trans_choice('core::general.id',1)}}</th>
                                                    <th>{{trans_choice('accounting::general.debit',1)}}</th>
                                                    <th>{{trans_choice('accounting::general.credit',1)}}</th>
                                                    <th>{{trans_choice('savings::general.balance',1)}}</th>
                                                    <th>{{trans_choice('core::general.action',1)}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $balance = 0;
                                                ?>
                                                @foreach($savings->transactions as $key)
                                                    <?php
                                                    if ($key->reversed == 0) {
                                                        $balance = $balance + $key->credit - $key->debit;
                                                    }
                                                    ?>
                                                    <tr @if($key->reversed == 1) style="text-decoration: line-through; opacity: 0.6;" @endif>
                                                        <td>
                                                            {{$key->date}}
                                                            @if($key->reversed == 1)
                                                                <span class="badge badge-danger">{{trans_choice('savings::general.reversed',1)}}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$key->submitted_on}}</td>
                                                        <td>{{$key->savings_transaction_type->name}}</td>
                                                        <td>{{$key->id}}</td>
                                                        <td>{{number_format($key->debit,$savings->decimals)}}</td>
                                                        <td>{{number_format($key->credit,$savings->decimals)}}</td>
                                                        <td>{{number_format($balance,$savings->decimals)}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                    {{trans_choice('core::general.action',1)}}
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                                    @can('savings.savings.transactions.show')
                                                                        <li>
                                                                            <a href="{{url('savings/transaction/'.$key->id.'/show')}}" class="dropdown-item">
                                                                                <i class="fas fa-search"></i>
                                                                                {{trans_choice('core::general.detail',2)}}
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('savings.savings.transactions.edit')
                                                                        @if($key->reversed==0)
                                                                            <li>
                                                                                <a href="{{url('savings/transaction/'.$key->id.'/reverse')}}" class="dropdown-item confirm">
                                                                                    <i class="fas fa-undo"></i>
                                                                                    {{trans_choice('savings::general.reverse',1)}}
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endcan
                                                                    @can('savings.savings.transactions.show')
                                                                        <li>
                                                                            <a href="{{url('savings/transaction/'.$key->id.'/print')}}" target="_blank" class="dropdown-item">
                                                                                <i class="fas fa-print"></i>
                                                                                {{trans_choice('core::general.print',1)}}
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{url('savings/transaction/'.$key->id.'/pdf')}}" target="_blank" class="dropdown-item">
                                                                                <i class="fas fa-file-pdf"></i>
                                                                                {{trans_choice('core::general.download',1)}} {{trans_choice('core::general.pdf',1)}}
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endcan
                            @endif

                            <!-- Charges Tab -->
                            @can('savings.savings.charges.index')
                                <div class="tab-pane" id="savings_charges">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{trans_choice('core::general.name',1)}}</th>
                                                <th>{{trans_choice('savings::general.charge',1)}} {{trans_choice('core::general.type',1)}}</th>
                                                <th>{{trans_choice('core::general.amount',1)}}</th>
                                                <th>{{trans_choice('savings::general.collected_on',1)}}</th>
                                                <th>{{trans_choice('core::general.date',1)}}</th>
                                                <th>{{trans_choice('core::general.action',1)}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($savings->charges as $key)
                                                <tr>
                                                    <td>{{$key->name}}</td>
                                                    <td>{{$key->savings_charge->savings_charge_type->name}}</td>
                                                    <td>
                                                        @if($key->savings_charge_option_id==1)
                                                            {{number_format($key->amount,2)}}
                                                        @endif
                                                        @if($key->savings_charge_option_id==2)
                                                            {{number_format($key->amount,2)}}
                                                            % {{trans_choice('savings::general.percentage_of_amount',1)}}
                                                        @endif
                                                        @if($key->savings_charge_option_id==3)
                                                            {{number_format($key->amount,2)}}
                                                            % {{trans_choice('savings::general.percentage_of_savings_balance',1)}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($key->savings_charge->savings_charge_type_id==1)
                                                            {{trans_choice('savings::general.savings_activation',1)}}
                                                        @endif
                                                        @if($key->savings_charge->savings_charge_type_id==2)
                                                            {{trans_choice('savings::general.specified_due_date',1)}}
                                                        @endif
                                                        @if($key->savings_charge->savings_charge_type_id==3)
                                                            {{trans_choice('savings::general.withdrawal_fee',1)}}
                                                        @endif
                                                        @if($key->savings_charge->savings_charge_type_id==4)
                                                            {{trans_choice('savings::general.annual_fee',1)}}
                                                        @endif
                                                        @if($key->savings_charge->savings_charge_type_id==5)
                                                            {{trans_choice('savings::general.monthly_fee',1)}}
                                                        @endif
                                                        @if($key->savings_charge->savings_charge_type_id==6)
                                                            {{trans_choice('savings::general.inactivity_fee',1)}}
                                                        @endif
                                                        @if($key->savings_charge->savings_charge_type_id==7)
                                                            {{trans_choice('savings::general.quarterly_fee',1)}}
                                                        @endif
                                                    </td>
                                                    <td>{{$key->date}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                {{trans_choice('core::general.action',1)}}
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                                @can('savings.savings.charges.edit')
                                                                    <li>
                                                                        <a href="{{url('savings/charge/'.$key->id.'/edit')}}" class="dropdown-item">
                                                                            <i class="fas fa-edit"></i>
                                                                            {{trans_choice('core::general.edit',1)}}
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                                @can('savings.savings.charges.destroy')
                                                                    <li>
                                                                        <a href="{{url('savings/charge/'.$key->id.'/destroy')}}" class="dropdown-item confirm">
                                                                            <i class="fas fa-trash"></i>
                                                                            {{trans_choice('core::general.delete',1)}}
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endcan

                            <!-- Files Tab -->
                            @can('savings.savings.files.index')
                                <div class="tab-pane" id="savings_files">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{trans_choice('core::general.name',1)}}</th>
                                                <th>{{trans_choice('core::general.status',1)}}</th>
                                                <th>{{trans_choice('core::general.description',1)}}</th>
                                                <th>{{trans_choice('core::general.action',1)}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($savings->files as $key)
                                                <tr>
                                                    <td>{{$key->name}}</td>
                                                    <td>
                                                        @if($key->status==='pending')
                                                            <span class="badge badge-warning">{{trans_choice('client::general.pending',1)}}</span>
                                                        @endif
                                                        @if($key->status==='approved')
                                                            <span class="badge badge-success">{{trans_choice('client::general.approved',1)}}</span>
                                                        @endif
                                                        @if($key->status==='rejected')
                                                            <span class="badge badge-danger">{{trans_choice('client::general.rejected',1)}}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$key->description}}</td>
                                                    <td>
                                                        <a href="{{asset('storage/uploads/savings/'.$key->link)}}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        @can('savings.savings.files.edit')
                                                            <a href="{{url('savings/file/'.$key->id.'/edit')}}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan
                                                        @can('savings.savings.files.destroy')
                                                            <a href="{{url('savings/file/'.$key->id.'/destroy')}}" class="btn btn-sm btn-danger confirm">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @can('savings.savings.approve_savings')
            <!-- Approve Modal -->
            <div class="modal fade modern-modal" id="approve_savings_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('savings::general.approve',1) }} {{ trans_choice('savings::general.savings',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/approve_savings') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="approved_on_date" class="control-label">{{ trans_choice('core::general.date',1) }}</label>
                                    <flat-pickr class="form-control @error('approved_on_date') is-invalid @enderror"
                                                name="approved_on_date"
                                                value="{{date("Y-m-d")}}"
                                                id="approved_on_date" required>
                                    </flat-pickr>
                                </div>
                                <div class="form-group">
                                    <label for="approved_notes" class="control-label">{{ trans_choice('core::general.note',2) }}</label>
                                    <textarea name="approved_notes" class="form-control" id="approved_notes" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade modern-modal" id="reject_savings_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('savings::general.reject',1) }} {{ trans_choice('savings::general.savings',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/reject_savings') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="rejected_notes" class="control-label">{{ trans_choice('core::general.note',2) }}</label>
                                    <textarea name="rejected_notes" class="form-control" id="rejected_notes" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Withdraw Modal -->
            <div class="modal fade modern-modal" id="withdraw_savings_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('savings::general.withdraw',1) }} {{ trans_choice('savings::general.savings',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/withdraw_savings') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="withdrawn_notes" class="control-label">{{ trans_choice('core::general.note',2) }}</label>
                                    <textarea name="withdrawn_notes" class="form-control" id="withdrawn_notes" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-undo"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        @can('savings.savings.activate_savings')
            <!-- Activate Modal -->
            <div class="modal fade modern-modal" id="activate_savings_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('savings::general.activate',1) }} {{ trans_choice('savings::general.savings',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/activate_savings') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="activated_on_date" class="control-label">{{ trans_choice('savings::general.activation',1) }} {{ trans_choice('core::general.date',1) }}</label>
                                    <flat-pickr class="form-control @error('activated_on_date') is-invalid @enderror"
                                                name="activated_on_date"
                                                value="{{date("Y-m-d")}}"
                                                id="activated_on_date" required>
                                    </flat-pickr>
                                </div>
                                <div class="form-group">
                                    <label for="activated_notes" class="control-label">{{ trans_choice('core::general.note',2) }}</label>
                                    <textarea name="activated_notes" class="form-control" id="activated_notes" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-flag"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        @can('savings.savings.edit')
            <!-- Change Officer Modal -->
            <div class="modal fade modern-modal" id="change_savings_officer_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('savings::general.change',1) }} {{ trans_choice('savings::general.savings',1) }} {{ trans_choice('savings::general.officer',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/change_savings_officer') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="savings_officer_id" class="control-label">{{trans_choice('savings::general.savings',1)}} {{trans_choice('savings::general.officer',1)}}</label>
                                    <select class="form-control select2" name="savings_officer_id" id="savings_officer_id" v-model="savings_officer_id" required>
                                        <option value=""></option>
                                        @foreach($users as $key)
                                            <option value="{{$key->id}}" @if($key->id==$savings->savings_officer_id) selected @endif>{{$key->first_name}} {{$key->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        @can('savings.savings.close_savings')
            <!-- Close Account Modal -->
            <div class="modal fade modern-modal" id="close_savings_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('core::general.close',1) }} {{ trans_choice('savings::general.savings',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/close_savings') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="closed_notes" class="control-label">{{ trans_choice('core::general.note',2) }}</label>
                                    <textarea name="closed_notes" class="form-control" id="closed_notes" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-lock"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        @can('savings.savings.charges.create')
            <!-- Add Charge Modal -->
            <div class="modal fade modern-modal" id="add_charge_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ trans_choice('core::general.add',1) }} {{ trans_choice('savings::general.charge',1) }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('savings/'.$savings->id.'/charge/store') }}">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="savings_charge_id" class="control-label">{{trans_choice('savings::general.charge',1)}}</label>
                                    <select class="form-control @error('savings_charge_id') is-invalid @enderror"
                                            name="savings_charge_id" id="savings_charge_id"
                                            v-model="savings_charge_id" @change="changeCharge" required>
                                        <option value="">{{ trans_choice('core::general.select',1) }}</option>
                                        <option v-for="(charge,index) in charges" v-bind:value="index">
                                            @{{ charge.name }}
                                        </option>
                                    </select>
                                    @error('savings_charge_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="charge_amount" class="control-label">{{trans('core::general.amount')}}</label>
                                    <input type="text" name="amount" value="{{ old('amount') }}" id="charge_amount" v-model="amount"
                                           class="form-control numeric @error('amount') is-invalid @enderror" 
                                           :readonly="!canOverride" 
                                           :style="!canOverride ? 'background-color: #e9ecef; cursor: not-allowed;' : ''" 
                                           required>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small v-if="!canOverride" class="form-text text-muted">
                                        Amount is fixed for this charge type and cannot be modified.
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="charge_date" class="control-label">{{trans('core::general.date')}}</label>
                                    <input type="date" name="date" v-model="date" id="charge_date"
                                           class="form-control @error('date') is-invalid @enderror" required>
                                    @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    {{ trans_choice('core::general.close',1) }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ trans_choice('core::general.save',1) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </section>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                savings_officer_id: '{{old('savings_officer_id',$savings->savings_officer_id)}}',
                savings_charge_id: "{{ old('savings_charge_id') }}",
                amount: "{{ old('amount') }}",
                date: "{{ old('date',date('Y-m-d')) }}",
                charges: charges,
                canOverride: true
            },
            methods: {
                changeCharge() {
                    if (this.savings_charge_id && this.charges[this.savings_charge_id]) {
                        const selectedCharge = this.charges[this.savings_charge_id];
                        
                        // Check if charge allows override
                        if (selectedCharge.allow_override == 1) {
                            this.canOverride = true;
                            // Keep current amount or set to charge amount if empty
                            if (!this.amount) {
                                this.amount = selectedCharge.amount;
                            }
                        } else {
                            this.canOverride = false;
                            // Set amount to charge's fixed amount
                            this.amount = selectedCharge.amount;
                        }
                    } else {
                        this.canOverride = true;
                        this.amount = '';
                    }
                }
            },
            mounted() {
                // Check on page load if there's a pre-selected charge
                if (this.savings_charge_id) {
                    this.changeCharge();
                }
            }
        })
    </script>
@endsection
