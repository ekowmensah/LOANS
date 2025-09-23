<div class="grid-stack-item cash_flow_chart"
     gs-x="{{$config["x"]}}" gs-y="{{$config["y"]}}"
     gs-w="{{$config["width"]}}" gs-h="{{$config["height"]}}" gs-id="CashFlowChart">
    <div class="grid-stack-item-content">
        <div class="card h-100">
            <div class="card-header bg-gradient-info text-white">
                <h3 class="card-title">
                    <i class="fas fa-chart-area mr-2"></i>
                    Cash Flow Analysis
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool text-white trash-item" onclick="remove_widget('CashFlowChart')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="text-success">
                                <i class="fas fa-arrow-circle-up fa-2x"></i>
                            </div>
                            <h5 class="text-success mt-2">Cash Inflow</h5>
                            <p class="text-muted">Repayments + Deposits + Income</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="text-warning">
                                <i class="fas fa-arrow-circle-down fa-2x"></i>
                            </div>
                            <h5 class="text-warning mt-2">Cash Outflow</h5>
                            <p class="text-muted">Disbursements + Withdrawals + Expenses</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="text-primary">
                                <i class="fas fa-balance-scale fa-2x"></i>
                            </div>
                            <h5 class="text-primary mt-2">Net Flow</h5>
                            <p class="text-muted">Inflow - Outflow</p>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container" style="height: 300px;">
                    <canvas id="cashFlowChart"></canvas>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Cash Flow Insights:</strong>
                            Monitor your institution's liquidity and cash management efficiency over time.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('cashFlowChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $months !!},
                datasets: [{
                    label: 'Cash Inflow',
                    data: {!! $cash_inflow !!},
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Cash Outflow',
                    data: {!! $cash_outflow !!},
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Net Cash Flow',
                    data: {!! $net_flow !!},
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    borderDash: [5, 5]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
});
</script>
