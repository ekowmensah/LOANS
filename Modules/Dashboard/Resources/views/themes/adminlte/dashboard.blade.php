@extends('core::layouts.master')
@section('title')
    {{trans_choice('dashboard::general.dashboard',1)}}
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Ultra Modern Financial Dashboard */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        body {
            background: #0f1419;
        }
        
        .content-wrapper {
            background: #0f1419;
            padding: 0 !important;
        }
        
        .content {
            padding: 0 !important;
        }
        
        /* Modern Dashboard Container */
        .dashboard-container {
            background: #0f1419;
            min-height: 100vh;
            padding: 30px;
        }
        
        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px 0;
        }
        
        .dashboard-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .dashboard-subtitle {
            color: #8b949e;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .top-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-box {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 10px 15px;
            color: #c9d1d9;
            width: 300px;
            transition: all 0.3s;
        }
        
        .search-box:focus {
            outline: none;
            border-color: #58a6ff;
            background: #0d1117;
        }
        
        .action-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            border-color: #58a6ff;
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(88, 166, 255, 0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            background: rgba(59, 130, 246, 0.1);
            color: #58a6ff;
        }
        
        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
        }
        
        .stat-trend.up {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .stat-trend.down {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .stat-label {
            color: #8b949e;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .stat-value {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        
        .stat-description {
            color: #8b949e;
            font-size: 13px;
        }
        
        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        /* Chart Card */
        .chart-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 24px;
            height: 400px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
        }
        
        .chart-tabs {
            display: flex;
            gap: 10px;
        }
        
        .chart-tab {
            background: transparent;
            border: 1px solid #30363d;
            color: #8b949e;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .chart-tab.active {
            background: #58a6ff;
            border-color: #58a6ff;
            color: #ffffff;
        }
        
        /* Activity Feed */
        .activity-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 24px;
            height: 400px;
            overflow-y: auto;
        }
        
        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #30363d;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }
        
        .activity-icon.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .activity-icon.warning {
            background: rgba(251, 146, 60, 0.1);
            color: #fb923c;
        }
        
        .activity-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .activity-desc {
            color: #8b949e;
            font-size: 12px;
        }
        
        .activity-time {
            color: #6e7681;
            font-size: 11px;
            margin-top: 4px;
        }
        
        /* Widget Container */
        .widget-container {
            background: transparent;
            padding: 0;
        }
        
        .grid-stack-item {
            border-radius: 12px;
        }
        
        .grid-stack-item-content {
            border-radius: 12px;
            background: #161b22;
            border: 1px solid #30363d;
            transition: all 0.3s ease;
        }
        
        .grid-stack-item-content:hover {
            border-color: #58a6ff;
            box-shadow: 0 12px 40px rgba(88, 166, 255, 0.15);
        }
        
        .trash-item {
            position: absolute;
            bottom: 10px;
            right: 10px;
            display: none;
            z-index: 100;
        }
        
        .grid-stack-item-content:hover .trash-item {
            display: block;
        }
        
        .trash-item button {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .trash-item button:hover {
            background: #dc2626;
        }
        
        /* Cards */
        .card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid #30363d;
            color: #ffffff;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .card-body {
            color: #c9d1d9;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
        }
        
        .empty-state-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: rgba(88, 166, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #58a6ff;
            font-size: 40px;
        }
        
        .empty-state h3 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: #8b949e;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        /* Modal Enhancements */
        .modal-content {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
        }
        
        .modal-header {
            background: transparent;
            border-bottom: 1px solid #30363d;
            color: #ffffff;
        }
        
        .modal-header .close {
            color: #8b949e;
            opacity: 1;
        }
        
        .modal-body {
            padding: 30px;
            color: #c9d1d9;
        }
        
        .modal-footer {
            border-top: 1px solid #30363d;
            padding: 20px 30px;
        }
        
        .modal-body .form-control,
        .modal-body .form-select {
            background: #0d1117;
            border: 1px solid #30363d;
            color: #c9d1d9;
            border-radius: 8px;
        }
        
        .modal-body .form-control:focus {
            border-color: #58a6ff;
            background: #0d1117;
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.1);
        }
        
        .modal-body label {
            color: #8b949e;
            font-weight: 500;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0d1117;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #30363d;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #484f58;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card {
            animation: fadeInUp 0.5s ease;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
            
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .top-actions {
                width: 100%;
                flex-direction: column;
            }
            
            .search-box {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@stop
@section('content')
    @php
        $activeLoans = \Modules\Loan\Entities\Loan::where('status', 'active')->count();
        $pendingLoans = \Modules\Loan\Entities\Loan::where('status', 'pending')->count();
        $totalClients = \Modules\Client\Entities\Client::where('status', 'active')->count();
        $portfolioValue = \Modules\Loan\Entities\Loan::where('status', 'active')->sum('principal_outstanding_derived');
        $totalDisbursed = \Modules\Loan\Entities\Loan::where('status', 'active')->sum('principal_disbursed_derived');
        $totalCollected = \Modules\Loan\Entities\Loan::where('status', 'active')->sum('principal_repaid_derived');
        $collectionRate = $totalDisbursed > 0 ? ($totalCollected / $totalDisbursed) * 100 : 0;
        $recentLoans = \Modules\Loan\Entities\Loan::with('client')->latest()->take(5)->get();
    @endphp
    
    <div class="dashboard-container" id="app">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h1 class="dashboard-title">Financial Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back, {{ Auth::user()->first_name }} • {{ date('l, F j, Y') }}</p>
            </div>
            <div class="top-actions">
                <input type="text" class="search-box" placeholder="Search loans, clients, transactions...">
                <button class="action-btn" data-toggle="modal" data-target="#add_widget">
                    <i class="fas fa-plus"></i>
                    Add Widget
                </button>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> 12.5%
                    </div>
                </div>
                <div class="stat-label">Portfolio Value</div>
                <div class="stat-value">₵{{ number_format($portfolioValue, 2) }}</div>
                <div class="stat-description">Total outstanding amount</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> {{ $activeLoans }}
                    </div>
                </div>
                <div class="stat-label">Active Loans</div>
                <div class="stat-value">{{ $activeLoans }}</div>
                <div class="stat-description">Currently performing</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> Active
                    </div>
                </div>
                <div class="stat-label">Total Clients</div>
                <div class="stat-value">{{ $totalClients }}</div>
                <div class="stat-description">Registered clients</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-trend {{ $collectionRate >= 80 ? 'up' : 'down' }}">
                        <i class="fas fa-arrow-{{ $collectionRate >= 80 ? 'up' : 'down' }}"></i> {{ number_format($collectionRate, 1) }}%
                    </div>
                </div>
                <div class="stat-label">Collection Rate</div>
                <div class="stat-value">{{ number_format($collectionRate, 1) }}%</div>
                <div class="stat-description">Payment performance</div>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="content-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Loan Performance</h3>
                    <div class="chart-tabs">
                        <button class="chart-tab active">7D</button>
                        <button class="chart-tab">1M</button>
                        <button class="chart-tab">3M</button>
                        <button class="chart-tab">1Y</button>
                    </div>
                </div>
                <canvas id="performanceChart" style="max-height: 320px;"></canvas>
            </div>
            
            <div class="activity-card">
                <div class="chart-header">
                    <h3 class="chart-title">Recent Activity</h3>
                </div>
                @foreach($recentLoans as $loan)
                <div class="activity-item">
                    <div class="activity-icon {{ $loan->status === 'active' ? 'success' : ($loan->status === 'pending' ? 'warning' : 'info') }}">
                        <i class="fas fa-{{ $loan->status === 'active' ? 'check' : ($loan->status === 'pending' ? 'clock' : 'file') }}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Loan #{{ $loan->id }} - {{ $loan->client ? $loan->client->first_name . ' ' . $loan->client->last_name : 'N/A' }}</div>
                        <div class="activity-desc">₵{{ number_format($loan->principal, 2) }} • {{ ucfirst($loan->status) }}</div>
                        <div class="activity-time">{{ $loan->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Widgets Section -->
        <div class="widget-container">
            <div class="grid-stack">
                @foreach($user_widgets as $user_widget)
                    @widget($user_widget->class, ['x' => $user_widget->x,"y"=>$user_widget->y,"width"=>$user_widget->width,"height"=>$user_widget->height])
                @endforeach
            </div>
            
            @if(empty($user_widgets) || count($user_widgets) == 0)
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <h3>No Widgets Added Yet</h3>
                    <p>Customize your dashboard by adding financial widgets to track<br>your key metrics and performance indicators.</p>
                    <button data-toggle="modal" data-target="#add_widget" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        Add Your First Widget
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Add Widget Modal -->
        <div class="modal fade" id="add_widget">
            <div class="modal-dialog modal-lg">
                <form method="post" action="{{ url('dashboard/store_widget') }}">
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Add Dashboard Widget
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="widget_id">
                                    <i class="fas fa-puzzle-piece mr-2"></i>
                                    Select Widget
                                </label>
                                <select class="form-control form-control-lg" name="widget_id" id="widget_id" required>
                                    <option value="">Choose a widget...</option>
                                    @foreach($available_widgets as $key=>$value)
                                        <option value="{{$key}}">{{$value["name"]}}</option>
                                    @endforeach
                                </select>
                                <small class="form-text" style="color: #8b949e;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Widgets can be resized and repositioned after adding
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </button>
                            <button type="submit" class="action-btn">
                                <i class="fas fa-check mr-2"></i>
                                Add Widget
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        let app = new Vue({
            el: '#app'
        });
        
        // Initialize Chart
        const ctx = document.getElementById('performanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Disbursements',
                        data: [12, 19, 3, 5, 2, 3, 7],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Collections',
                        data: [8, 15, 7, 9, 6, 8, 10],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#c9d1d9'
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                color: '#8b949e'
                            },
                            grid: {
                                color: '#30363d'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#8b949e'
                            },
                            grid: {
                                color: '#30363d'
                            }
                        }
                    }
                }
            });
        }
        
        // GridStack initialization
        var grid = GridStack.init({
            animate: true,
            float: true,
            resizable: {
                handles: 'e, se, s, sw, w'
            },
            draggable: {
                handle: '.card-header'
            }
        });
        
        grid.on('change', function (event, items) {
            let data = [];
            if (items) {
                items.forEach(function (item, index) {
                    data[index] = {
                        "id": item.id,
                        "x": item.x,
                        "y": item.y,
                        "width": item.w,
                        "height": item.h
                    };
                });
            }
            axios.post('{{url('dashboard/update_widget_positions')}}', {
                widgets: data,
                _token: '{{csrf_token()}}'
            });
        });
        
        function remove_widget(widget_id) {
            if (confirm('Remove this widget?')) {
                axios.post('{{url('dashboard/remove_widget')}}', {
                    id: widget_id,
                    _token: '{{csrf_token()}}'
                }).then(function (response) {
                    location.reload();
                });
            }
        }
    </script>
@endsection
