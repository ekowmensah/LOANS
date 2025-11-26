@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.manage',1) }} {{ trans_choice('core::general.menu',1) }}
@endsection
@section('styles')

    <style>
        body.dragging, body.dragging * {
            cursor: move !important;
        }

        .dragged {
            position: absolute;
            opacity: 0.5;
            z-index: 2000;
        }

        ul.sortable li.placeholder {
            position: relative;
            /** More li styles **/
        }

        ul.sortable li.placeholder:before {
            position: absolute;
            /** Define arrowhead **/
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('core::general.manage',1) }} {{ trans_choice('core::general.menu',2) }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.manage',1) }} {{ trans_choice('core::general.menu',2) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <div class="card">
            <div class="card-header">
                <div class="btn-group">
                    <div class="dropdown">
                        <a href="#" class="btn btn-trigger btn-icon dropdown-toggle"
                           data-toggle="dropdown">
                            <i class="fas fa-wrench"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xs">
                            <a class="dropdown-item"><span>Show</span></a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>10])}}"
                               class="dropdown-item {{request('per_page')==10?'active':''}}">
                                10
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>20])}}"
                               class="dropdown-item {{(request('per_page')==20||!request('per_page'))?'active':''}}">
                                20
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>50])}}"
                               class="dropdown-item {{request('per_page')==50?'active':''}}">50</a>
                            <a class="dropdown-item">Order</a>
                            <a href="{{request()->fullUrlWithQuery(['order_by_dir'=>'asc'])}}"
                               class="dropdown-item {{(request('order_by_dir')=='asc'||!request('order_by_dir'))?'active':''}}">
                                ASC
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['order_by_dir'=>'desc'])}}"
                               class="dropdown-item {{request('order_by_dir')=='desc'?'active':''}}">
                                DESC
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-tools">
                    <button class="btn btn-info btn-sm" onclick="save_menu()">
                        {{ trans_choice('core::general.save',1) }}
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <ul class="sortable list-group" id="space0">
                    @foreach($data as $parent)
                        @if($parent->children->count()==0)
                            <li class="list-group-item" data-id="{{$parent->id}}" data-name="{{$parent->name}}">
                                <i class="{{$parent->icon}}"></i> <span class="menu_title">{{$parent->name}}</span>
                            </li>
                        @else
                            <li class="list-group-item" data-id="{{$parent->id}}" data-name="{{$parent->name}}">
                                <i class="{{$parent->icon}}"></i> <span class="menu_title">{{$parent->name}}</span>
                                <ul class="list-group" style="margin-top: 10px">
                                    @foreach($parent->children as $child)
                                        <li class="list-group-item" data-id="{{$child->id}}"
                                            data-name="{{$child->name}}"><i
                                                    class="{{$child->icon}}"></i>
                                            <span class="menu_title">{{$child->name}}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/jquery.editable/jquery.editable.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortable@0.9.13/source/js/jquery-sortable-min.js"></script>
    <script>
        var group = $("ul.sortable").sortable({
            group: 'nested',
            nested: true
        });

        function save_menu() {
            var data = group.sortable("serialize").get();

            var jsonString = JSON.stringify(data, null, ' ');
            console.log('Saving menu data:', jsonString);
            
            axios.post('{{url('menu/update')}}', {
                data: data,
                _token: "{{csrf_token()}}"
            }).then(function (response) {
                console.log('Save response:', response);
                // Try modern Swal first, fallback to old swal, then toastr
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        text: response.data.msg,
                        icon: 'success',
                        showCancelButton: false,
                        timer: 1500
                    });
                } else if (typeof swal !== 'undefined') {
                    swal({
                        text: response.data.msg,
                        type: 'success',
                        showCancelButton: false,
                        timer: 1500
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.success(response.data.msg);
                } else {
                    alert(response.data.msg);
                }
            }).catch(function (error) {
                console.error('Save error:', error);
                var errorMsg = 'Failed to save menu order';
                if (error.response && error.response.data && error.response.data.message) {
                    errorMsg = error.response.data.message;
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        text: errorMsg,
                        icon: 'error'
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                } else {
                    alert(errorMsg);
                }
            });
        }

        $("span.menu_title").editable({
            callback: function (data) {
                if (data.content) {
                    $(data.$el).closest('li').attr('data-name', data.content);
                }
            }
        });
    </script>
@endsection
