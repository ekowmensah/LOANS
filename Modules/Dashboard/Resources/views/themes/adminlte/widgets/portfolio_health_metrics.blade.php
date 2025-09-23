<div class="grid-stack-item portfolio_health_metrics"
     gs-x="{{$config["x"]}}" gs-y="{{$config["y"]}}"
     gs-w="{{$config["width"]}}" gs-h="{{$config["height"]}}" gs-id="PortfolioHealthMetrics">
    <div class="grid-stack-item-content">
        <div class="card h-100">
            <div class="card-header bg-gradient-warning text-white">
                <h3 class="card-title">
                    <i class="fas fa-heartbeat mr-2"></i>
                    Portfolio Health Metrics
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool text-white trash-item" onclick="remove_widget('PortfolioHealthMetrics')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Key Performance Indicators -->
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-{{ $par_30 > 5 ? 'danger' : ($par_30 > 2 ? 'warning' : 'success') }}">
                                <i class="fas fa-percentage"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">PAR 30</span>
                                <span class="info-box-number">{{number_format($par_30, 2)}}%</span>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $par_30 > 5 ? 'danger' : ($par_30 > 2 ? 'warning' : 'success') }}" 
                                         style="width: {{min($par_30 * 10, 100)}}%"></div>
                                </div>
                                <span class="progress-description">Portfolio at Risk</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-{{ $collection_efficiency > 90 ? 'success' : ($collection_efficiency > 70 ? 'warning' : 'danger') }}">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Collection Efficiency</span>
                                <span class="info-box-number">{{number_format($collection_efficiency, 1)}}%</span>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $collection_efficiency > 90 ? 'success' : ($collection_efficiency > 70 ? 'warning' : 'danger') }}" 
                                         style="width: {{$collection_efficiency}}%"></div>
                                </div>
                                <span class="progress-description">This Month</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-{{ $portfolio_yield > 15 ? 'success' : ($portfolio_yield > 10 ? 'info' : 'warning') }}">
                                <i class="fas fa-coins"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Portfolio Yield</span>
                                <span class="info-box-number">{{number_format($portfolio_yield, 2)}}%</span>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $portfolio_yield > 15 ? 'success' : ($portfolio_yield > 10 ? 'info' : 'warning') }}" 
                                         style="width: {{min($portfolio_yield * 5, 100)}}%"></div>
                                </div>
                                <span class="progress-description">Return on Portfolio</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-{{ $default_rate < 5 ? 'success' : ($default_rate < 10 ? 'warning' : 'danger') }}">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Default Rate</span>
                                <span class="info-box-number">{{number_format($default_rate, 2)}}%</span>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $default_rate < 5 ? 'success' : ($default_rate < 10 ? 'warning' : 'danger') }}" 
                                         style="width: {{min($default_rate * 10, 100)}}%"></div>
                                </div>
                                <span class="progress-description">Loans at Risk</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Portfolio Overview -->
                    <div class="col-lg-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Portfolio Overview</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-success">
                                                <i class="fas fa-caret-up"></i> {{number_format(($total_outstanding / max($total_portfolio_value, 1)) * 100, 1)}}%
                                            </span>
                                            <h5 class="description-header">{{number_format($total_portfolio_value, 0)}}</h5>
                                            <span class="description-text">TOTAL PORTFOLIO</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="description-block">
                                            <span class="description-percentage text-warning">
                                                <i class="fas fa-caret-left"></i> {{number_format(($total_overdue / max($total_outstanding, 1)) * 100, 1)}}%
                                            </span>
                                            <h5 class="description-header">{{number_format($total_outstanding, 0)}}</h5>
                                            <span class="description-text">OUTSTANDING</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="progress mt-3">
                                    <div class="progress-bar bg-success" style="width: {{($total_portfolio_value - $total_outstanding) / max($total_portfolio_value, 1) * 100}}%"></div>
                                    <div class="progress-bar bg-warning" style="width: {{($total_outstanding - $total_overdue) / max($total_portfolio_value, 1) * 100}}%"></div>
                                    <div class="progress-bar bg-danger" style="width: {{$total_overdue / max($total_portfolio_value, 1) * 100}}%"></div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-success">■ Repaid</small>
                                    <small class="text-warning ml-2">■ Current</small>
                                    <small class="text-danger ml-2">■ Overdue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title">Client Metrics</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="description-block border-right">
                                            <h5 class="description-header text-primary">{{number_format($total_clients)}}</h5>
                                            <span class="description-text">TOTAL CLIENTS</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="description-block">
                                            <h5 class="description-header text-success">{{number_format($active_borrowers)}}</h5>
                                            <span class="description-text">ACTIVE BORROWERS</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Client Retention Rate:</span>
                                        <strong class="text-{{ $client_retention_rate > 80 ? 'success' : ($client_retention_rate > 60 ? 'warning' : 'danger') }}">
                                            {{number_format($client_retention_rate, 1)}}%
                                        </strong>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-{{ $client_retention_rate > 80 ? 'success' : ($client_retention_rate > 60 ? 'warning' : 'danger') }}" 
                                             style="width: {{$client_retention_rate}}%"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Total Loans:</span>
                                        <strong>{{number_format($total_loans_count)}}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Loans at Risk:</span>
                                        <strong class="text-danger">{{number_format($loans_at_risk_count)}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-{{ $par_30 > 5 ? 'danger' : ($par_30 > 2 ? 'warning' : 'success') }}">
                            <h5><i class="icon fas fa-{{ $par_30 > 5 ? 'exclamation-triangle' : ($par_30 > 2 ? 'exclamation-circle' : 'check') }}"></i> Portfolio Health Status</h5>
                            @if($par_30 > 5)
                                Your portfolio shows high risk with PAR 30 above 5%. Immediate attention required for collection strategies.
                            @elseif($par_30 > 2)
                                Portfolio shows moderate risk. Monitor closely and implement preventive measures.
                            @else
                                Excellent portfolio health! Continue current risk management practices.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
