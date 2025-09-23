@extends('core::layouts.master')
@section('title')
    Group Loan Member Allocations
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Group Loan Member Allocations
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan')}}">{{ trans_choice('loan::general.loan',2) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/'.$loan->id.'/show')}}">Loan #{{$loan->id}}</a></li>
                        <li class="breadcrumb-item active">Member Allocations</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-12">
                <!-- Loan Summary Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Loan Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Loan ID:</strong> #{{$loan->id}}
                            </div>
                            <div class="col-md-3">
                                <strong>Group:</strong> 
                                <a href="{{url('client/group/'.$loan->group_id.'/show')}}">{{$loan->group->name}}</a>
                            </div>
                            <div class="col-md-3">
                                <strong>Principal:</strong> {{number_format($loan->principal, 2)}}
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong> 
                                <span class="badge badge-{{$loan->status == 'active' ? 'success' : 'secondary'}}">{{ucfirst($loan->status)}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Member Allocations Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Member Allocations</h3>
                        <div class="card-tools">
                            @if(!$loan->memberAllocations()->exists())
                                @can('client.groups.manage_members')
                                    <a href="{{url('client/loan/'.$loan->id.'/member-allocations/create')}}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Create Allocations
                                    </a>
                                @endcan
                            @else
                                @can('client.groups.manage_members')
                                    <a href="{{url('client/loan/'.$loan->id.'/member-allocations/edit')}}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit Allocations
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($loan->memberAllocations()->exists())
                            <div class="table-responsive">
                                <table class="table table-striped" id="allocations-table">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Allocated Amount</th>
                                            <th>Percentage</th>
                                            <th>Total Paid</th>
                                            <th>Outstanding</th>
                                            <th>Payment %</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($loan->memberAllocations as $allocation)
                                        <tr>
                                            <td>{{$allocation->client->first_name}} {{$allocation->client->last_name}}</td>
                                            <td>{{number_format($allocation->allocated_amount, 2)}}</td>
                                            <td>{{$allocation->allocated_percentage}}%</td>
                                            <td>{{number_format($allocation->total_paid, 2)}}</td>
                                            <td>{{number_format($allocation->outstanding_balance, 2)}}</td>
                                            <td>{{$allocation->payment_percentage}}%</td>
                                            <td>
                                                <span class="badge badge-{{$allocation->status == 'active' ? 'success' : ($allocation->status == 'completed' ? 'primary' : 'danger')}}">
                                                    {{ucfirst($allocation->status)}}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{url('client/loan/'.$loan->id.'/member-allocations/'.$allocation->id)}}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No member allocations have been created for this group loan yet. 
                                @can('client.groups.manage_members')
                                    <a href="{{url('client/loan/'.$loan->id.'/member-allocations/create')}}">Create allocations</a> to track individual member contributions.
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Summary Card -->
                @if($loan->memberAllocations()->exists())
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-money-bill"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Allocated</span>
                                        <span class="info-box-number">{{number_format($loan->memberAllocations->sum('allocated_amount'), 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Paid</span>
                                        <span class="info-box-number">{{number_format($loan->memberAllocations->sum('total_paid'), 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Outstanding</span>
                                        <span class="info-box-number">{{number_format($loan->memberAllocations->sum('outstanding_balance'), 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Overall Progress</span>
                                        <span class="info-box-number">
                                            @php
                                                $totalAllocated = $loan->memberAllocations->sum('allocated_amount');
                                                $totalPaid = $loan->memberAllocations->sum('total_paid');
                                                $progress = $totalAllocated > 0 ? round(($totalPaid / $totalAllocated) * 100, 1) : 0;
                                            @endphp
                                            {{$progress}}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @if($loan->memberAllocations()->exists())
    <script>
        $(document).ready(function() {
            $('#allocations-table').DataTable({
                processing: false,
                serverSide: false,
                order: [[0, 'asc']],
                pageLength: 25
            });
        });
    </script>
    @endif
@endsection
