@extends('core::layouts.master')

@section('title')
    My Clients
@endsection

@section('styles')
    <style>
        .client-card {
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        .client-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .stat-badge {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }
        .client-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }
        .client-initial {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 24px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-users"></i> My Clients
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('field-agent/dashboard')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">My Clients</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $clients->count() }}</h3>
                        <p>Total Clients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $clients->where('status', 'active')->count() }}</h3>
                        <p>Active Clients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $clients->filter(function($c) { return $c->loans->where('status', 'active')->count() > 0; })->count() }}</h3>
                        <p>With Active Loans</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $clients->filter(function($c) { return $c->savings->count() > 0; })->count() }}</h3>
                        <p>With Savings</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i> Client List
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search clients...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($clients->count() > 0)
                    <div class="row" id="clientsList">
                        @foreach($clients as $client)
                            <div class="col-md-6 col-lg-4 client-item" data-name="{{ strtolower($client->first_name . ' ' . $client->last_name) }}" data-mobile="{{ $client->mobile }}">
                                <div class="card client-card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3">
                                                @if($client->photo)
                                                    <img src="{{asset('storage/'.$client->photo)}}" class="client-photo" alt="{{ $client->first_name }}">
                                                @else
                                                    <div class="client-initial">
                                                        {{ strtoupper(substr($client->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">
                                                    <a href="{{url('client/' . $client->id . '/show')}}">
                                                        {{ $client->first_name }} {{ $client->last_name }}
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    <i class="fas fa-id-card"></i> {{ $client->external_id ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-phone"></i> {{ $client->mobile ?? 'N/A' }}
                                                </p>
                                                @if($client->email)
                                                    <p class="mb-2">
                                                        <i class="fas fa-envelope"></i> {{ $client->email }}
                                                    </p>
                                                @endif
                                                
                                                <!-- Status Badge -->
                                                <div class="mb-2">
                                                    @if($client->status == 'active')
                                                        <span class="badge badge-success stat-badge">Active</span>
                                                    @elseif($client->status == 'pending')
                                                        <span class="badge badge-warning stat-badge">Pending</span>
                                                    @else
                                                        <span class="badge badge-secondary stat-badge">{{ ucfirst($client->status) }}</span>
                                                    @endif
                                                </div>

                                                <!-- Account Information -->
                                                <div class="mt-2">
                                                    @php
                                                        $activeLoans = $client->loans->where('status', 'active')->count();
                                                        $savingsAccounts = $client->savings->count();
                                                    @endphp
                                                    
                                                    @if($activeLoans > 0)
                                                        <span class="badge badge-info stat-badge mr-1">
                                                            <i class="fas fa-money-bill-wave"></i> {{ $activeLoans }} Loan(s)
                                                        </span>
                                                    @endif
                                                    
                                                    @if($savingsAccounts > 0)
                                                        <span class="badge badge-primary stat-badge">
                                                            <i class="fas fa-piggy-bank"></i> {{ $savingsAccounts }} Savings
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="mt-3">
                                                    <a href="{{url('client/' . $client->id . '/show')}}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> View Details
                                                    </a>
                                                    @if($activeLoans > 0)
                                                        <a href="{{url('field-agent/collection/create?client_id=' . $client->id)}}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-money-bill"></i> Collect
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You don't have any clients assigned yet. Please contact your supervisor.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchInput').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                $('.client-item').each(function() {
                    var name = $(this).data('name');
                    var mobile = $(this).data('mobile');
                    
                    if (name.includes(searchTerm) || mobile.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endsection
