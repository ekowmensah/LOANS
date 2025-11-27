@extends('core::layouts.master')

@section('title')
    {{ trans_choice('fieldagent::general.collection', 1) }} - {{ $collection->receipt_number }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('fieldagent::general.collection', 1) }} Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/collection') }}">{{ trans_choice('fieldagent::general.collection', 2) }}</a></li>
                        <li class="breadcrumb-item active">{{ $collection->receipt_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Collection Information</h3>
                        <div class="card-tools">
                            {!! $collection->status_badge !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 200px;">Receipt Number:</th>
                                <td><strong>{{ $collection->receipt_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Field Agent:</th>
                                <td>{{ $collection->fieldAgent->agent_code }} - {{ $collection->fieldAgent->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Client:</th>
                                <td>{{ $collection->client->first_name }} {{ $collection->client->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Collection Type:</th>
                                <td>{{ $collection->collection_type_label }}</td>
                            </tr>
                            <tr>
                                <th>Reference Account:</th>
                                <td>
                                    @if($reference)
                                        @if($collection->collection_type == 'savings_deposit')
                                            {{ $reference->savingsProduct->name ?? 'N/A' }} - {{ $reference->account_number ?? 'N/A' }}
                                        @elseif($collection->collection_type == 'loan_repayment')
                                            Loan #{{ $reference->id }} - {{ $reference->loanProduct->name ?? 'N/A' }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td><strong class="text-success">{{ number_format($collection->amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Payment Method:</th>
                                <td>{{ ucwords(str_replace('_', ' ', $collection->payment_method)) }}</td>
                            </tr>
                            <tr>
                                <th>Collection Date:</th>
                                <td>{{ $collection->collection_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Collection Time:</th>
                                <td>{{ $collection->collection_time ? $collection->collection_time->format('H:i') : 'N/A' }}</td>
                            </tr>
                            @if($collection->notes)
                                <tr>
                                    <th>Notes:</th>
                                    <td>{{ $collection->notes }}</td>
                                </tr>
                            @endif
                        </table>

                        @if($collection->status == 'verified')
                            <hr>
                            <h5>Verification Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 200px;">Verified By:</th>
                                    <td>{{ $collection->verifiedBy->first_name ?? 'N/A' }} {{ $collection->verifiedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Verified At:</th>
                                    <td>{{ $collection->verified_at ? $collection->verified_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        @endif

                        @if($collection->status == 'posted')
                            <hr>
                            <h5>Posting Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 200px;">Posted By:</th>
                                    <td>{{ $collection->postedBy->first_name ?? 'N/A' }} {{ $collection->postedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Posted At:</th>
                                    <td>{{ $collection->posted_at ? $collection->posted_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        @endif

                        @if($collection->status == 'rejected')
                            <hr>
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> Rejection Details</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 200px;">Rejected By:</th>
                                        <td>{{ $collection->verifiedBy->first_name ?? 'N/A' }} {{ $collection->verifiedBy->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Rejected At:</th>
                                        <td>{{ $collection->verified_at ? $collection->verified_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reason:</th>
                                        <td>{{ $collection->rejection_reason }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if($collection->canBeVerified() && auth()->user()->can('field_agent.collections.verify'))
                            <form method="post" action="{{ url('field-agent/collection/' . $collection->id . '/verify') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Verify this collection?')">
                                    <i class="fas fa-check"></i> Verify Collection
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> Reject Collection
                            </button>
                        @endif

                        @if($collection->canBePosted() && auth()->user()->can('field_agent.collections.post'))
                            <a href="{{ url('field-agent/collection/' . $collection->id . '/post') }}" class="btn btn-primary" onclick="return confirm('Post this collection to accounting?')">
                                <i class="fas fa-paper-plane"></i> Post to Accounting
                            </a>
                        @endif

                        <a href="{{ url('field-agent/collection') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Location Card -->
                @if($collection->latitude && $collection->longitude)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">GPS Location</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Coordinates:</strong><br>
                                Lat: {{ $collection->latitude }}<br>
                                Long: {{ $collection->longitude }}
                            </p>
                            @if($collection->location_address)
                                <p><strong>Address:</strong><br>{{ $collection->location_address }}</p>
                            @endif
                            <a href="https://maps.google.com/?q={{ $collection->latitude }},{{ $collection->longitude }}" target="_blank" class="btn btn-sm btn-primary btn-block">
                                <i class="fas fa-map-marker-alt"></i> View on Google Maps
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Photo Proof Card -->
                @if($collection->photo_proof)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Photo Proof</h3>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset($collection->photo_proof) }}" alt="Photo Proof" class="img-fluid" style="max-width: 100%;">
                        </div>
                        <div class="card-footer">
                            <a href="{{ asset($collection->photo_proof) }}" target="_blank" class="btn btn-sm btn-default btn-block">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Reject Modal -->
    @if($collection->canBeVerified())
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ url('field-agent/collection/' . $collection->id . '/reject') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Collection</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="rejection_reason">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Collection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
