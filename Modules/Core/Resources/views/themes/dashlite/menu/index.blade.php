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
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('core::general.manage',1) }} {{ trans_choice('core::general.menu',1) }}</h3>

            <div class="box-tools pull-right">
                <button class="btn btn-info btn-sm" onclick="save_menu()">
                    {{ trans_choice('core::general.save',1) }}
                </button>

            </div>
        </div>
        <div class="box-body table-responsive">
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
                                    <li class="list-group-item" data-id="{{$child->id}}" data-name="{{$child->name}}"><i
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
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/jquery-sortable/js/jquery-sortable-min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.editable/jquery.editable.min.js') }}"></script>
    <script>
        var group = $("ul.sortable").sortable({});

        function save_menu() {
            var data = group.sortable("serialize").get();

            var jsonString = JSON.stringify(data, null, ' ');
            console.log('Saving menu data:', jsonString);
            
            axios.post('{{url('menu/update')}}', {
                data: data,
                _token:"{{csrf_token()}}"
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
