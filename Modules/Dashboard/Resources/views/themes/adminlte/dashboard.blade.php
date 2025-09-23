@extends('core::layouts.master')
@section('title')
    {{trans_choice('dashboard::general.dashboard',1)}}
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    <style>
        .trash-item {
            position: absolute;
            bottom: 0;
            right: 20px;
            display: none;
        }

        .grid-stack-item-content:hover .trash-item {
            display: block;
            position: absolute;
            bottom: 0;
            right: 20px;
        }
        
        /* Enhanced Dashboard Styles */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        
        .dashboard-stats {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .widget-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .grid-stack-item {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .grid-stack-item-content {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .grid-stack-item-content:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .small-box {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .small-box:hover {
            transform: scale(1.05);
        }
        
        .info-box {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        
        .info-box:hover {
            transform: translateY(-3px);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        .btn-dashboard {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .dashboard-metric {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 10px;
            margin: 10px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .metric-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .progress {
            height: 8px;
            border-radius: 10px;
        }
        
        .progress-bar {
            border-radius: 10px;
        }
    </style>
@stop
@section('content')
    <!-- Enhanced Dashboard Header -->
    <section class="dashboard-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        {{trans_choice('dashboard::general.dashboard',1)}}
                    </h1>
                    <p class="mb-0 mt-2 opacity-75">Financial Management Overview</p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="dashboard-stats">
                        <div class="row">
                            <div class="col-4">
                                <div class="dashboard-metric">
                                    <div class="metric-value text-success">{{ \Modules\Loan\Entities\Loan::where('status', 'active')->count() }}</div>
                                    <div class="metric-label">Active Loans</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="dashboard-metric">
                                    <div class="metric-value text-info">{{ \Modules\Client\Entities\Client::count() }}</div>
                                    <div class="metric-label">Total Clients</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="dashboard-metric">
                                    <div class="metric-value text-warning">{{ number_format(\Modules\Loan\Entities\Loan::where('status', 'active')->sum('principal'), 0) }}</div>
                                    <div class="metric-label">Portfolio Value</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div>
            <div class="row">
                <div class="col-md-12" id="app">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="get" class="d-inline-flex">
                                <flat-pickr value="{{request('date_range')}}"
                                            placeholder="Select data range"
                                            :config="{
                                                        mode: 'range'
                                                       }"
                                            class="form-control mr-2  @error('date_range') is-invalid @enderror"
                                            name="date_range" id="date_range">
                                </flat-pickr>
                                <button
                                        class="btn btn-info margin float-right">
                                    {{trans_choice('core::general.filter',1)}}
                                </button>
                            </form>
                        </div>
                        <div></div>
                        <div>
                            <button data-toggle="modal" data-target="#add_widget"
                                    class="btn btn-primary btn-dashboard ml-2 margin float-right">
                                <i class="fas fa-plus mr-2"></i>
                                {{trans_choice('core::general.add',1)}}  {{trans_choice('dashboard::general.widget',1)}}
                            </button>
                        </div>
                    </div>


                    <div class="modal fade" id="add_widget">
                        <div class="modal-dialog modal-lg">
                            <form method="post" action="{{ url('dashboard/store_widget') }}"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h4 class="modal-title">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            {{trans_choice('core::general.add',1)}}  {{trans_choice('dashboard::general.widget',1)}}
                                        </h4>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="widget_id" class="control-label font-weight-bold">
                                                <i class="fas fa-puzzle-piece mr-2"></i>
                                                Select {{trans_choice('dashboard::general.widget',1)}}
                                            </label>
                                            <select class="form-control form-control-lg" name="widget_id" id="widget_id" required>
                                                <option value="">Choose a widget to add...</option>
                                                @foreach($available_widgets as $key=>$value)
                                                    <option value="{{$key}}" data-description="Enhanced financial widget">
                                                        {{$value["name"]}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Widgets can be resized and repositioned after adding.
                                            </small>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h6 class="text-muted">Available Widget Categories:</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-primary">
                                                            <div class="card-body text-center">
                                                                <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                                                <h6>Financial Overview</h6>
                                                                <small class="text-muted">Comprehensive financial metrics</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-outline card-success">
                                                            <div class="card-body text-center">
                                                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                                                <h6>Group Loans</h6>
                                                                <small class="text-muted">Group lending analytics</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">
                                            <i class="fas fa-times mr-2"></i>
                                            {{trans_choice('core::general.close',1)}}
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-dashboard">
                                            <i class="fas fa-plus mr-2"></i>
                                            {{trans_choice('core::general.save',1)}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-container">
                        <div class="grid-stack">
                            @foreach($user_widgets as $user_widget)
                                @widget($user_widget->class, ['x' =>
                                $user_widget->x,"y"=>$user_widget->y,"width"=>$user_widget->width,"height"=>$user_widget->height])
                            @endforeach
                        </div>
                        
                        @if(empty($user_widgets) || count($user_widgets) == 0)
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-chart-line fa-5x text-muted"></i>
                                </div>
                                <h4 class="text-muted">Welcome to Your Enhanced Dashboard</h4>
                                <p class="text-muted mb-4">Start by adding financial widgets to monitor your loan portfolio, cash flow, and business performance.</p>
                                <button data-toggle="modal" data-target="#add_widget" class="btn btn-primary btn-dashboard btn-lg">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Your First Widget
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        let app = new Vue({
            el: '#app'
        })
        
        // Enhanced GridStack initialization with better options
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
            }).then(function (response) {
                // Success feedback with modern toast
                if (typeof toastr !== 'undefined') {
                    toastr.success("Dashboard layout updated successfully!");
                }
            }).catch(function (error) {
                if (typeof toastr !== 'undefined') {
                    toastr.error("Failed to update dashboard layout");
                }
            });
        });
        
        // Widget removal function
        function remove_widget(widget_id) {
            if (confirm('Are you sure you want to remove this widget?')) {
                axios.post('{{url('dashboard/remove_widget')}}', {
                    id: widget_id,
                    _token: '{{csrf_token()}}'
                }).then(function (response) {
                    location.reload();
                }).catch(function (error) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error("Failed to remove widget");
                    }
                });
            }
        }
        
        // Enhanced widget selection with preview
        document.addEventListener('DOMContentLoaded', function() {
            const widgetSelect = document.getElementById('widget_id');
            if (widgetSelect) {
                widgetSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        // Add visual feedback for selection
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });
            }
            
            // Add loading animation for better UX
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding Widget...';
                        submitBtn.disabled = true;
                    }
                });
            });
        });
        
        // Real-time dashboard updates (optional)
        function refreshDashboardMetrics() {
            // This can be implemented to refresh key metrics without full page reload
            console.log('Dashboard metrics refreshed');
        }
        
        // Auto-refresh every 5 minutes (optional)
        // setInterval(refreshDashboardMetrics, 300000);
    </script>
@endsection
