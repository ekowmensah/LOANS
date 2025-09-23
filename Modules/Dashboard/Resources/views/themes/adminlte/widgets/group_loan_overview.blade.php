<div class="grid-stack-item group_loan_overview"
     gs-x="{{$config["x"]}}" gs-y="{{$config["y"]}}"
     gs-w="{{$config["width"]}}" gs-h="{{$config["height"]}}" gs-id="GroupLoanOverview">
    <div class="grid-stack-item-content">
        <div class="card h-100">
            <div class="card-header bg-gradient-success text-white">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    Group Loan Overview
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool text-white trash-item" onclick="remove_widget('GroupLoanOverview')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Group Statistics -->
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{number_format($total_groups)}}</h3>
                                <p>Total Groups</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{number_format($active_groups)}}</h3>
                                <p>Active Groups</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{number_format($total_group_loans)}}</h3>
                                <p>Group Loans</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{number_format($avg_group_size, 1)}}</h3>
                                <p>Avg Group Size</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Portfolio Metrics -->
                    <div class="col-lg-4">
                        <div class="info-box bg-gradient-primary">
                            <span class="info-box-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Group Portfolio</span>
                                <span class="info-box-number">{{number_format($total_group_portfolio, 2)}}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Total Value
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Outstanding</span>
                                <span class="info-box-number">{{number_format($total_group_outstanding, 2)}}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{$total_group_portfolio > 0 ? ($total_group_outstanding / $total_group_portfolio) * 100 : 0}}%"></div>
                                </div>
                                <span class="progress-description">
                                    {{$total_group_portfolio > 0 ? number_format(($total_group_outstanding / $total_group_portfolio) * 100, 1) : 0}}% of Portfolio
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="info-box bg-gradient-{{ $group_par > 5 ? 'danger' : ($group_par > 2 ? 'warning' : 'success') }}">
                            <span class="info-box-icon">
                                <i class="fas fa-chart-pie"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Group PAR</span>
                                <span class="info-box-number">{{number_format($group_par, 2)}}%</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{min($group_par, 100)}}%"></div>
                                </div>
                                <span class="progress-description">
                                    Portfolio at Risk
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Performance Analysis -->
                    <div class="col-lg-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">Group Performance</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-success">
                                                <i class="fas fa-caret-up"></i> {{$total_groups > 0 ? number_format(($active_groups / $total_groups) * 100, 1) : 0}}%
                                            </span>
                                            <h5 class="description-header">{{number_format($active_groups)}}</h5>
                                            <span class="description-text">ACTIVE GROUPS</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="description-block">
                                            <span class="description-percentage text-{{ $groups_at_risk < 5 ? 'success' : 'danger' }}">
                                                <i class="fas fa-{{ $groups_at_risk < 5 ? 'check' : 'exclamation-triangle' }}"></i> {{number_format($groups_at_risk)}}
                                            </span>
                                            <h5 class="description-header">{{number_format($active_group_loans)}}</h5>
                                            <span class="description-text">ACTIVE LOANS</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Group Utilization:</span>
                                        <strong class="text-info">
                                            {{$total_groups > 0 ? number_format(($active_groups / $total_groups) * 100, 1) : 0}}%
                                        </strong>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-info" 
                                             style="width: {{$total_groups > 0 ? ($active_groups / $total_groups) * 100 : 0}}%"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Risk Level:</span>
                                        <strong class="text-{{ $group_par > 5 ? 'danger' : ($group_par > 2 ? 'warning' : 'success') }}">
                                            {{$group_par > 5 ? 'High' : ($group_par > 2 ? 'Medium' : 'Low')}}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title">Recent Activity</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <i class="fas fa-hand-holding-usd text-success fa-2x"></i>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="text-success">{{number_format($recent_group_disbursements)}}</h4>
                                        <small class="text-muted">Disbursements (30 days)</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <i class="fas fa-file-alt text-primary fa-2x"></i>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="text-primary">{{number_format($recent_group_applications)}}</h4>
                                        <small class="text-muted">New Applications (30 days)</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-percentage text-warning fa-2x"></i>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="text-warning">{{number_format($group_par, 1)}}%</h4>
                                        <small class="text-muted">Current PAR</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-{{ $group_par > 5 ? 'danger' : ($group_par > 2 ? 'warning' : 'info') }}">
                            <h5><i class="icon fas fa-{{ $group_par > 5 ? 'exclamation-triangle' : ($group_par > 2 ? 'exclamation-circle' : 'info-circle') }}"></i> Group Loan Insights</h5>
                            @if($group_par > 5)
                                High risk detected in group loans. Consider reviewing group formation criteria and implementing stronger peer guarantee mechanisms.
                            @elseif($group_par > 2)
                                Moderate risk in group portfolio. Monitor group dynamics and provide additional training on financial management.
                            @else
                                Group loan portfolio performing well. Group lending methodology is effective with strong peer support systems.
                            @endif
                            <br><small>Average group size: {{number_format($avg_group_size, 1)}} members | Active groups: {{number_format($active_groups)}} of {{number_format($total_groups)}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
