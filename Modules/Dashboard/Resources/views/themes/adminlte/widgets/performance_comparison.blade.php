<div class="grid-stack-item performance_comparison"
     gs-x="{{$config["x"]}}" gs-y="{{$config["y"]}}"
     gs-w="{{$config["width"]}}" gs-h="{{$config["height"]}}" gs-id="PerformanceComparison">
    <div class="grid-stack-item-content">
        <div class="card h-100">
            <div class="card-header bg-gradient-dark text-white">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Performance Comparison
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool text-white trash-item" onclick="remove_widget('PerformanceComparison')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Period Selector -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-period="current">
                                {{$current_month}}
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-period="previous">
                                vs {{$previous_month}}
                            </button>
                            <button type="button" class="btn btn-outline-info" data-period="yearly">
                                vs {{$last_year_month}}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics Comparison -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Loan Portfolio</h3>
                            </div>
                            <div class="card-body">
                                <!-- Disbursements -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">Disbursements</h6>
                                        <h4 class="text-primary mb-0">{{number_format($current_disbursements, 2)}}</h4>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-{{ $disbursements_mom_change >= 0 ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $disbursements_mom_change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                            {{number_format(abs($disbursements_mom_change), 1)}}%
                                        </span>
                                        <br>
                                        <small class="text-muted">vs last month</small>
                                    </div>
                                </div>
                                
                                <!-- Repayments -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Repayments</h6>
                                        <h4 class="text-success mb-0">{{number_format($current_repayments, 2)}}</h4>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-{{ $repayments_mom_change >= 0 ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $repayments_mom_change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                            {{number_format(abs($repayments_mom_change), 1)}}%
                                        </span>
                                        <br>
                                        <small class="text-muted">vs last month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">Financial Performance</h3>
                            </div>
                            <div class="card-body">
                                <!-- Income -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">Income</h6>
                                        <h4 class="text-success mb-0">{{number_format($current_income, 2)}}</h4>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-{{ $income_mom_change >= 0 ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $income_mom_change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                            {{number_format(abs($income_mom_change), 1)}}%
                                        </span>
                                        <br>
                                        <small class="text-muted">vs last month</small>
                                    </div>
                                </div>
                                
                                <!-- Net Profit -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Net Profit</h6>
                                        <h4 class="text-{{ $current_profit >= 0 ? 'success' : 'danger' }} mb-0">{{number_format($current_profit, 2)}}</h4>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-{{ $profit_mom_change >= 0 ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $profit_mom_change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                            {{number_format(abs($profit_mom_change), 1)}}%
                                        </span>
                                        <br>
                                        <small class="text-muted">vs last month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Comparison Table -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Metric</th>
                                        <th class="text-center">{{$current_month}}</th>
                                        <th class="text-center">{{$previous_month}}</th>
                                        <th class="text-center">MoM Change</th>
                                        <th class="text-center">YoY Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Disbursements</strong></td>
                                        <td class="text-center">{{number_format($current_disbursements, 0)}}</td>
                                        <td class="text-center">{{number_format($previous_disbursements, 0)}}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $disbursements_mom_change >= 0 ? 'success' : 'danger' }}">
                                                {{$disbursements_mom_change >= 0 ? '+' : ''}}{{number_format($disbursements_mom_change, 1)}}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $disbursements_yoy_change >= 0 ? 'success' : 'danger' }}">
                                                {{$disbursements_yoy_change >= 0 ? '+' : ''}}{{number_format($disbursements_yoy_change, 1)}}%
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Repayments</strong></td>
                                        <td class="text-center">{{number_format($current_repayments, 0)}}</td>
                                        <td class="text-center">{{number_format($previous_repayments, 0)}}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $repayments_mom_change >= 0 ? 'success' : 'danger' }}">
                                                {{$repayments_mom_change >= 0 ? '+' : ''}}{{number_format($repayments_mom_change, 1)}}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $repayments_yoy_change >= 0 ? 'success' : 'danger' }}">
                                                {{$repayments_yoy_change >= 0 ? '+' : ''}}{{number_format($repayments_yoy_change, 1)}}%
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Income</strong></td>
                                        <td class="text-center">{{number_format($current_income, 0)}}</td>
                                        <td class="text-center">{{number_format($previous_income, 0)}}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $income_mom_change >= 0 ? 'success' : 'danger' }}">
                                                {{$income_mom_change >= 0 ? '+' : ''}}{{number_format($income_mom_change, 1)}}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $income_yoy_change >= 0 ? 'success' : 'danger' }}">
                                                {{$income_yoy_change >= 0 ? '+' : ''}}{{number_format($income_yoy_change, 1)}}%
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Expenses</strong></td>
                                        <td class="text-center">{{number_format($current_expenses, 0)}}</td>
                                        <td class="text-center">{{number_format($previous_expenses, 0)}}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $expenses_mom_change <= 0 ? 'success' : 'danger' }}">
                                                {{$expenses_mom_change >= 0 ? '+' : ''}}{{number_format($expenses_mom_change, 1)}}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $expenses_yoy_change <= 0 ? 'success' : 'danger' }}">
                                                {{$expenses_yoy_change >= 0 ? '+' : ''}}{{number_format($expenses_yoy_change, 1)}}%
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Savings Deposits</strong></td>
                                        <td class="text-center">{{number_format($current_savings, 0)}}</td>
                                        <td class="text-center">{{number_format($previous_savings, 0)}}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $savings_mom_change >= 0 ? 'success' : 'danger' }}">
                                                {{$savings_mom_change >= 0 ? '+' : ''}}{{number_format($savings_mom_change, 1)}}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $savings_yoy_change >= 0 ? 'success' : 'danger' }}">
                                                {{$savings_yoy_change >= 0 ? '+' : ''}}{{number_format($savings_yoy_change, 1)}}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Performance Insights -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb mr-2"></i>Performance Insights</h6>
                            <ul class="mb-0">
                                @if($profit_mom_change > 10)
                                    <li>Excellent profit growth of {{number_format($profit_mom_change, 1)}}% compared to last month</li>
                                @elseif($profit_mom_change < -10)
                                    <li>Profit declined by {{number_format(abs($profit_mom_change), 1)}}% - review cost management</li>
                                @endif
                                
                                @if($repayments_mom_change > 5)
                                    <li>Strong collection performance with {{number_format($repayments_mom_change, 1)}}% increase in repayments</li>
                                @elseif($repayments_mom_change < -5)
                                    <li>Collection efficiency decreased by {{number_format(abs($repayments_mom_change), 1)}}% - strengthen follow-up</li>
                                @endif
                                
                                @if($disbursements_yoy_change > 20)
                                    <li>Outstanding year-over-year growth in disbursements ({{number_format($disbursements_yoy_change, 1)}}%)</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Period selector functionality
    const periodButtons = document.querySelectorAll('[data-period]');
    periodButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            periodButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you could implement AJAX to load different period data
            console.log('Selected period:', this.dataset.period);
        });
    });
});
</script>
