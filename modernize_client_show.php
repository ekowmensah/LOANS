<?php
/**
 * Automated Script to Modernize Client Show Page
 * Run this script to apply all modern banking enhancements
 * 
 * Usage: php modernize_client_show.php
 */

echo "===========================================\n";
echo "Client Show Page Modernization Script\n";
echo "===========================================\n\n";

$filePath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/show.blade.php';
$backupPath = __DIR__ . '/Modules/Client/Resources/views/themes/adminlte/client/show_backup_' . date('Y_m_d_His') . '.blade.php';

// Check if file exists
if (!file_exists($filePath)) {
    die("ERROR: Client show file not found at: $filePath\n");
}

echo "Step 1: Creating backup...\n";
if (!copy($filePath, $backupPath)) {
    die("ERROR: Failed to create backup\n");
}
echo "✓ Backup created: $backupPath\n\n";

echo "Step 2: Reading original file...\n";
$content = file_get_contents($filePath);
if ($content === false) {
    die("ERROR: Failed to read file\n");
}
echo "✓ File read successfully\n\n";

// ============================================
// STEP 3: Add Modern CSS Styles
// ============================================
echo "Step 3: Adding modern CSS styles...\n";

$modernCSS = <<<'CSS'
<style>
/* Modern Banking Styles */
.client-hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.status-badge-modern {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 15px;
}

.status-pending { background: #f39c12; }
.status-active { background: #27ae60; }
.status-rejected { background: #e74c3c; }
.status-inactive { background: #95a5a6; }

.stat-card-modern {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: none;
}

.stat-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin: 10px 0;
}

.stat-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

.stat-icon {
    font-size: 40px;
    color: #3498db;
    margin-bottom: 10px;
}

.info-card-modern {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    border: none;
}

.btn-banking-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    color: white;
}

.btn-banking-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-banking-success {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    color: white;
}

.btn-banking-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
    color: white;
}

.btn-banking-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    color: white;
}

.btn-banking-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
    color: white;
}

.approval-info-card {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 5px solid #27ae60;
}

.rejection-info-card {
    background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 5px solid #e74c3c;
}

.pending-alert-card {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 5px solid #f39c12;
}

@media (max-width: 768px) {
    .stat-card-modern {
        margin-bottom: 15px;
    }
    .stat-value {
        font-size: 24px;
    }
}
</style>
CSS;

// Add CSS after @section('styles')
$content = str_replace(
    "@section('styles')\n@stop",
    "@section('styles')\n" . $modernCSS . "\n@stop",
    $content
);
echo "✓ Modern CSS added\n\n";

// ============================================
// STEP 4: Add Quick Stats Section
// ============================================
echo "Step 4: Adding quick stats dashboard...\n";

$quickStats = <<<'STATS'

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-piggy-bank"></i></div>
                <div class="stat-value">
                    @php
                        $total_savings = \Modules\Savings\Entities\Savings::where('client_id', $client->id)->sum('balance_derived');
                    @endphp
                    {{ number_format($total_savings, 2) }}
                </div>
                <div class="stat-label">Total Savings</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="stat-value">
                    @php
                        $active_loans = \Modules\Loan\Entities\Loan::where('client_id', $client->id)->whereIn('status', ['active', 'disbursed'])->count();
                    @endphp
                    {{ $active_loans }}
                </div>
                <div class="stat-label">Active Loans</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-university"></i></div>
                <div class="stat-value">
                    @php
                        $total_accounts = \Modules\Savings\Entities\Savings::where('client_id', $client->id)->count();
                    @endphp
                    {{ $total_accounts }}
                </div>
                <div class="stat-label">Accounts</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card-modern">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-value">
                    {{ \Carbon\Carbon::parse($client->created_date)->format('M Y') }}
                </div>
                <div class="stat-label">Member Since</div>
            </div>
        </div>
    </div>

    <!-- Status-Based Alert Cards -->
    @if($client->status == 'pending')
    <div class="pending-alert-card">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x mr-3" style="color: #f39c12;"></i>
            <div class="flex-grow-1">
                <h5 class="mb-1" style="color: #f39c12; font-weight: 700;">Pending Approval</h5>
                <p class="mb-0" style="color: #7f8c8d;">This client is awaiting approval. Please review and approve or reject.</p>
            </div>
            @can('client.clients.activate')
            <div>
                <button class="btn btn-banking-success mr-2" data-toggle="modal" data-target="#approve_client_modal">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button class="btn btn-banking-danger" data-toggle="modal" data-target="#reject_client_modal">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
            @endcan
        </div>
    </div>
    @endif

    @if($client->status == 'active' && $client->approved_on_date)
    <div class="approval-info-card">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x mr-3" style="color: #27ae60;"></i>
            <div>
                <h5 class="mb-1" style="color: #27ae60; font-weight: 700;">Approved Client</h5>
                <p class="mb-0" style="color: #7f8c8d;">
                    Approved by <strong>{{ $client->approved_by_user->first_name ?? 'System' }} {{ $client->approved_by_user->last_name ?? '' }}</strong> 
                    on <strong>{{ \Carbon\Carbon::parse($client->approved_on_date)->format('d M Y') }}</strong>
                    @if($client->approved_notes)
                        <br><em>"{{ $client->approved_notes }}"</em>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    @if($client->status == 'rejected')
    <div class="rejection-info-card">
        <div class="d-flex align-items-center">
            <i class="fas fa-times-circle fa-2x mr-3" style="color: #e74c3c;"></i>
            <div class="flex-grow-1">
                <h5 class="mb-1" style="color: #e74c3c; font-weight: 700;">Rejected Client</h5>
                <p class="mb-0" style="color: #7f8c8d;">
                    Rejected by <strong>{{ $client->rejected_by_user->first_name ?? 'System' }} {{ $client->rejected_by_user->last_name ?? '' }}</strong> 
                    on <strong>{{ \Carbon\Carbon::parse($client->rejected_on_date)->format('d M Y') }}</strong>
                    @if($client->rejected_notes)
                        <br><strong>Reason:</strong> <em>"{{ $client->rejected_notes }}"</em>
                    @endif
                </p>
            </div>
            @can('client.clients.activate')
            <div>
                <a href="{{url('client/' . $client->id . '/undo_rejection')}}" class="btn btn-banking-primary confirm">
                    <i class="fas fa-undo"></i> Undo Rejection
                </a>
            </div>
            @endcan
        </div>
    </div>
    @endif

STATS;

// Add stats after the opening section tag
$content = str_replace(
    '<section class="content" id="app">',
    '<section class="content" id="app">' . $quickStats,
    $content
);
echo "✓ Quick stats dashboard added\n\n";

// ============================================
// STEP 5: Update Status Display
// ============================================
echo "Step 5: Updating status badges...\n";

$oldStatusDisplay = <<<'OLD'
                                <a class="float-right">
                                    <a class="float-right" data-toggle="modal"
                                       data-target="#change_status_modal" href="#">
                                        @if($client->status=='pending')
                                            {{trans_choice('core::general.pending',1)}}
                                        @endif
                                        @if($client->status=='active')
                                            {{trans_choice('core::general.active',1)}}
                                        @endif
                                        @if($client->status=='inactive')
                                            {{trans_choice('core::general.inactive',1)}}
                                        @endif
                                        @if($client->status=='deceased')
                                            {{trans_choice('core::general.deceased',1)}}
                                        @endif
                                        @if($client->status=='other')
                                            {{trans_choice('core::general.other',1)}}
                                        @endif
                                        @if($client->status=='closed')
                                            {{trans_choice('core::general.closed',1)}}
                                        @endif
                                    </a>
                                </a>
OLD;

$newStatusDisplay = <<<'NEW'
                                <span class="float-right">
                                    @if($client->status=='pending')
                                        <span class="status-badge-modern status-pending">{{trans_choice('core::general.pending',1)}}</span>
                                    @endif
                                    @if($client->status=='active')
                                        <span class="status-badge-modern status-active">{{trans_choice('core::general.active',1)}}</span>
                                    @endif
                                    @if($client->status=='inactive')
                                        <span class="status-badge-modern status-inactive">{{trans_choice('core::general.inactive',1)}}</span>
                                    @endif
                                    @if($client->status=='rejected')
                                        <span class="status-badge-modern status-rejected">Rejected</span>
                                    @endif
                                    @if($client->status=='deceased')
                                        <span class="status-badge-modern status-inactive">{{trans_choice('core::general.deceased',1)}}</span>
                                    @endif
                                    @if($client->status=='other')
                                        <span class="status-badge-modern status-inactive">{{trans_choice('core::general.other',1)}}</span>
                                    @endif
                                    @if($client->status=='closed')
                                        <span class="status-badge-modern status-inactive">{{trans_choice('core::general.closed',1)}}</span>
                                    @endif
                                </span>
NEW;

$content = str_replace($oldStatusDisplay, $newStatusDisplay, $content);
echo "✓ Status badges updated\n\n";

// ============================================
// STEP 6: Update Action Buttons
// ============================================
echo "Step 6: Updating action buttons...\n";

$oldButtons = <<<'OLD'
                        <div class="d-flex justify-content-center">
                            @can('client.clients.activate')
                                <a href="#" data-toggle="modal" class="btn btn-primary btn-sm  m-1"
                                   data-target="#change_status_modal">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{trans_choice('client::general.change',1)}} {{trans_choice('core::general.status',1)}}</span>
                                </a>
                            @endcan
                            @can('client.clients.edit')
                                <a href="{{url('client/' . $client->id . '/edit')}}"
                                   class="btn btn-primary btn-sm  m-1">
                                    <i class="fas fa-edit"></i>
                                    <span>{{trans_choice('core::general.edit',1)}}</span>
                                </a>

                                <a href="#" data-toggle="modal"
                                   data-target="#transfer_client_modal" class="btn btn-primary btn-sm m-1"><i
                                            class="fas fa-forward"></i>
                                    <span>{{trans_choice('client::general.transfer',1)}}</span>
                                </a>
                            @endcan
                        </div>
OLD;

$newButtons = <<<'NEW'
                        <div class="d-flex justify-content-center flex-wrap">
                            @if($client->status == 'pending')
                                @can('client.clients.activate')
                                    <button class="btn btn-banking-success m-1" data-toggle="modal" data-target="#approve_client_modal">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </button>
                                    <button class="btn btn-banking-danger m-1" data-toggle="modal" data-target="#reject_client_modal">
                                        <i class="fas fa-times-circle"></i> Reject
                                    </button>
                                @endcan
                            @endif
                            
                            @if($client->status == 'active')
                                @can('client.clients.activate')
                                    <a href="{{url('client/' . $client->id . '/undo_approval')}}" class="btn btn-warning m-1 confirm">
                                        <i class="fas fa-undo"></i> Undo Approval
                                    </a>
                                @endcan
                            @endif
                            
                            @can('client.clients.edit')
                                <a href="{{url('client/' . $client->id . '/edit')}}" class="btn btn-banking-primary m-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="#" data-toggle="modal" data-target="#transfer_client_modal" class="btn btn-banking-primary m-1">
                                    <i class="fas fa-forward"></i> Transfer
                                </a>
                            @endcan
                        </div>
NEW;

$content = str_replace($oldButtons, $newButtons, $content);
echo "✓ Action buttons updated\n\n";

// ============================================
// STEP 7: Add Approval Modals
// ============================================
echo "Step 7: Adding approval modals...\n";

$approvalModals = <<<'MODALS'

    <!-- Approve Client Modal -->
    <div class="modal fade" id="approve_client_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Client</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{url('client/' . $client->id . '/approve')}}">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to approve <strong>{{ $client->name }}</strong>?</p>
                        <div class="form-group">
                            <label for="approved_on_date">Approval Date <span class="text-danger">*</span></label>
                            <input type="date" name="approved_on_date" id="approved_on_date" class="form-control" value="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="approved_notes">Notes (Optional)</label>
                            <textarea name="approved_notes" id="approved_notes" class="form-control" rows="3" placeholder="Enter any approval notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-banking-success">
                            <i class="fas fa-check"></i> Approve Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Client Modal -->
    <div class="modal fade" id="reject_client_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Client</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{url('client/' . $client->id . '/reject')}}">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to reject <strong>{{ $client->name }}</strong>?</p>
                        <div class="form-group">
                            <label for="rejected_notes">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejected_notes" id="rejected_notes" class="form-control" rows="4" placeholder="Enter reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-banking-danger">
                            <i class="fas fa-times"></i> Reject Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

MODALS;

// Add modals before the final @endsection
$content = str_replace(
    "    </script>\n\n@endsection",
    "    </script>\n" . $approvalModals . "\n@endsection",
    $content
);
echo "✓ Approval modals added\n\n";

// ============================================
// STEP 8: Save Modified File
// ============================================
echo "Step 8: Saving modernized file...\n";
if (file_put_contents($filePath, $content) === false) {
    die("ERROR: Failed to save file\n");
}
echo "✓ File saved successfully\n\n";

// ============================================
// Summary
// ============================================
echo "===========================================\n";
echo "✅ MODERNIZATION COMPLETE!\n";
echo "===========================================\n\n";

echo "Changes Applied:\n";
echo "  ✓ Modern banking CSS styles\n";
echo "  ✓ Quick stats dashboard (Savings, Loans, Accounts)\n";
echo "  ✓ Status-based alert cards\n";
echo "  ✓ Modern status badges\n";
echo "  ✓ Updated action buttons\n";
echo "  ✓ Approval/Rejection modals\n\n";

echo "Backup Location:\n";
echo "  $backupPath\n\n";

echo "Original File:\n";
echo "  $filePath\n\n";

echo "Next Steps:\n";
echo "  1. Clear your browser cache\n";
echo "  2. Visit: /client/13/show\n";
echo "  3. Enjoy your modern banking interface!\n\n";

echo "To revert changes:\n";
echo "  copy \"$backupPath\" \"$filePath\"\n\n";

echo "===========================================\n";
echo "Script completed successfully!\n";
echo "===========================================\n";
