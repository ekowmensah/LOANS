<div class="grid-stack-item financial_alerts"
     gs-x="{{$config["x"]}}" gs-y="{{$config["y"]}}"
     gs-w="{{$config["width"]}}" gs-h="{{$config["height"]}}" gs-id="FinancialAlerts">
    <div class="grid-stack-item-content">
        <div class="card h-100">
            <div class="card-header bg-gradient-{{ $high_priority_alerts > 0 ? 'danger' : ($total_alerts > 0 ? 'warning' : 'success') }} text-white">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-2"></i>
                    Financial Alerts
                    @if($total_alerts > 0)
                        <span class="badge badge-light ml-2">{{$total_alerts}}</span>
                    @endif
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool text-white trash-item" onclick="remove_widget('FinancialAlerts')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($total_alerts > 0)
                    <div class="alert-summary mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="description-block">
                                    <h5 class="description-header text-danger">
                                        {{count(array_filter($alerts, function($alert) { return $alert['priority'] === 'high'; }))}}
                                    </h5>
                                    <span class="description-text">HIGH PRIORITY</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="description-block border-right border-left">
                                    <h5 class="description-header text-warning">
                                        {{count(array_filter($alerts, function($alert) { return $alert['priority'] === 'medium'; }))}}
                                    </h5>
                                    <span class="description-text">MEDIUM</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="description-block">
                                    <h5 class="description-header text-info">
                                        {{count(array_filter($alerts, function($alert) { return $alert['priority'] === 'low'; }))}}
                                    </h5>
                                    <span class="description-text">LOW</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alerts-container" style="max-height: 400px; overflow-y: auto;">
                        @foreach($alerts as $index => $alert)
                            <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-start">
                                    <div class="alert-icon mr-3">
                                        <i class="{{ $alert['icon'] }} fa-lg"></i>
                                    </div>
                                    <div class="alert-content flex-grow-1">
                                        <h6 class="alert-heading mb-1">
                                            {{ $alert['title'] }}
                                            <span class="badge badge-{{ $alert['priority'] === 'high' ? 'danger' : ($alert['priority'] === 'medium' ? 'warning' : 'info') }} ml-2">
                                                {{ strtoupper($alert['priority']) }}
                                            </span>
                                        </h6>
                                        <p class="mb-2">{{ $alert['message'] }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-lightbulb mr-1"></i>
                                            <strong>Recommended Action:</strong> {{ $alert['action'] }}
                                        </small>
                                    </div>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-primary btn-sm btn-block" onclick="refreshAlerts()">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Refresh Alerts
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-secondary btn-sm btn-block" onclick="markAllAsRead()">
                                    <i class="fas fa-check-double mr-2"></i>
                                    Mark All Read
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h5 class="text-success">All Clear!</h5>
                        <p class="text-muted mb-3">No financial alerts at this time. Your portfolio is performing well.</p>
                        
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div class="card card-outline card-success">
                                    <div class="card-body">
                                        <h6 class="card-title">System Health</h6>
                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                        <small class="text-muted">All systems operating normally</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-primary btn-sm mt-3" onclick="refreshAlerts()">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Check for Updates
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function refreshAlerts() {
    // Show loading state
    const refreshBtn = event.target;
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    // Simulate refresh (in real implementation, this would make an AJAX call)
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function markAllAsRead() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        alert.style.opacity = '0.5';
        setTimeout(() => {
            alert.remove();
        }, 300);
    });
    
    // Update alert counter
    setTimeout(() => {
        const alertBadge = document.querySelector('.card-title .badge');
        if (alertBadge) {
            alertBadge.textContent = '0';
        }
        
        // Show success message
        const alertsContainer = document.querySelector('.alerts-container');
        if (alertsContainer) {
            alertsContainer.innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-check text-success fa-2x mb-2"></i>
                    <p class="text-muted">All alerts marked as read</p>
                </div>
            `;
        }
    }, 500);
}

// Auto-refresh alerts every 5 minutes
setInterval(() => {
    // In a real implementation, this would make an AJAX call to refresh alerts
    console.log('Auto-refreshing financial alerts...');
}, 300000);
</script>
