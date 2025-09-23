<div class="grid-stack-item financial_overview"
     gs-x="{{$config["x"]}}" gs-y="{{$config["y"]}}"
     gs-w="{{$config["width"]}}" gs-h="{{$config["height"]}}" gs-id="FinancialOverview">
    <div class="grid-stack-item-content">
        <div class="card h-100">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>
                    Financial Overview
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool text-white trash-item" onclick="remove_widget('FinancialOverview')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Portfolio Metrics -->
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{number_format($total_loans_disbursed, 2)}}</h3>
                                <p>Loans Disbursed</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{number_format($total_loan_repayments, 2)}}</h3>
                                <p>Loan Repayments</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{number_format($total_outstanding, 2)}}</h3>
                                <p>Total Outstanding</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{number_format($total_overdue, 2)}}</h3>
                                <p>Total Overdue</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Savings & Wallet -->
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon">
                                <i class="fas fa-piggy-bank"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Savings Balance</span>
                                <span class="info-box-number">{{number_format($total_savings_balance, 2)}}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Deposits: {{number_format($total_savings_deposits, 2)}}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-gradient-info">
                            <span class="info-box-icon">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Wallet Balance</span>
                                <span class="info-box-number">{{number_format($total_wallet_balance, 2)}}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    Digital Wallets
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="info-box bg-gradient-{{ $par_ratio > 5 ? 'danger' : ($par_ratio > 2 ? 'warning' : 'success') }}">
                            <span class="info-box-icon">
                                <i class="fas fa-chart-pie"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Portfolio at Risk</span>
                                <span class="info-box-number">{{number_format($par_ratio, 2)}}%</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{min($par_ratio, 100)}}%"></div>
                                </div>
                                <span class="progress-description">
                                    Risk Assessment
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Financial Performance -->
                    <div class="col-lg-4">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Income vs Expenses</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> Income: {{number_format($total_income, 2)}}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-danger">
                                        <i class="fas fa-arrow-down"></i> Expenses: {{number_format($total_expenses, 2)}}
                                    </span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong class="text-{{ $net_profit >= 0 ? 'success' : 'danger' }}">
                                        Net Profit: {{number_format($net_profit, 2)}}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title">Cash Flow</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="text-success">
                                        <i class="fas fa-plus-circle"></i> Inflow: {{number_format($cash_inflow, 2)}}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-warning">
                                        <i class="fas fa-minus-circle"></i> Outflow: {{number_format($cash_outflow, 2)}}
                                    </span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong class="text-{{ $net_cash_flow >= 0 ? 'success' : 'danger' }}">
                                        Net Flow: {{number_format($net_cash_flow, 2)}}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">Quick Stats</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span>Total Portfolio:</span>
                                    <strong>{{number_format($total_loans_disbursed, 0)}}</strong>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Collection Rate:</span>
                                    <strong class="text-success">
                                        {{$total_loans_disbursed > 0 ? number_format(($total_loan_repayments / $total_loans_disbursed) * 100, 1) : 0}}%
                                    </strong>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Liquidity Ratio:</span>
                                    <strong class="text-info">
                                        {{$cash_outflow > 0 ? number_format(($total_savings_balance + $total_wallet_balance) / $cash_outflow, 2) : 'N/A'}}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
