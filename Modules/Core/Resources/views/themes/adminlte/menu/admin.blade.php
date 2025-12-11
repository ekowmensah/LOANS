<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <i class="fa fa-user"></i>
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        @php
            // Check if user is a field agent by checking the field_agents table
            $isFieldAgent = \Modules\FieldAgent\Entities\FieldAgent::where('user_id', Auth::id())->exists();
        @endphp
        
        <!-- search form -->
        @if(!$isFieldAgent)
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
            </form>
        @endif
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            @if($isFieldAgent)
                {{-- Field Agent Menu - Only Dashboard and Field Agent Functions --}}
                <li class="@if(Request::is('field-agent/dashboard')) active @endif">
                    <a href="{{url('field-agent/dashboard')}}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="@if(Request::is('field-agent/my-clients*')) active @endif">
                    <a href="{{url('field-agent/my-clients')}}">
                        <i class="fas fa-users"></i>
                        <span>My Clients</span>
                    </a>
                </li>
                <li class="@if(Request::is('field-agent/collection*')) active @endif">
                    <a href="{{url('field-agent/collection')}}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Collections</span>
                    </a>
                </li>
                <li class="@if(Request::is('field-agent/daily-report*')) active @endif">
                    <a href="{{url('field-agent/daily-report')}}">
                        <i class="fas fa-file-alt"></i>
                        <span>Daily Reports</span>
                    </a>
                </li>
            @else
                {{-- Regular Admin Menu --}}
                @foreach(\Modules\Core\Entities\Menu::with('children')->where('is_parent',1)->orderBy('menu_order','asc')->get() as $parent)
                    @if($parent->children->count()==0)
                        @if(!empty($parent->permissions))
                            @can($parent->permissions)
                                <li class=" @if(Request::is($parent->url)) active @endif"><a href="{{url($parent->url)}}"><i class="{{$parent->icon}}"></i>
                                        <span>{{$parent->name}}</span></a>
                                </li>
                            @endcan
                        @else
                            <li class=" @if(Request::is($parent->url)) active @endif"><a href="{{url($parent->url)}}"><i class="{{$parent->icon}}"></i>
                                    <span>{{$parent->name}}</span></a>
                            </li>
                        @endif
                    @else
                        @if(!empty($parent->permissions))
                            @can($parent->permissions)
                                <li class="treeview @if(Request::is($parent->url.'*')) active @endif">
                                    <a href="{{url($parent->url)}}">
                                        <i class="{{$parent->icon}}"></i> <span>{{$parent->name}}</span>
                                        <span class="pull-right-container"><i
                                                    class="fa fa-angle-left pull-right"></i></span>
                                    </a>
                                    <ul class="treeview-menu">
                                        @foreach($parent->children as $child)
                                            @if(!empty($child->permissions))
                                                @can($child->permissions)
                                                    <li class=" @if(Request::is($child->url)) active @endif"> <a href="{{url($child->url)}}"><i
                                                                    class="{{$child->icon}}"></i> {{$child->name}}</a>
                                                    </li>
                                                @endcan
                                            @else
                                                <li class=" @if(Request::is($child->url)) active @endif"><a href="{{url($child->url)}}"><i
                                                                class="{{$child->icon}}"></i> {{$child->name}}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endcan
                        @else
                            <li class="treeview @if(Request::is($parent->url.'*')) active @endif">
                                <a href="{{url($parent->url)}}">
                                    <i class="{{$parent->icon}}"></i> <span>{{$parent->name}}</span>
                                    <span class="pull-right-container"><i
                                                class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @foreach($parent->children as $child)
                                        @if(!empty($child->permissions))
                                            @can($child->permissions)
                                                <li class=" @if(Request::is($child->url)) active @endif"><a href="{{url($child->url)}}"><i
                                                                class="{{$child->icon}}"></i> {{$child->name}}</a>
                                                </li>
                                            @endcan
                                        @else
                                            <li class=" @if(Request::is($child->url)) active @endif"><a href="{{url($child->url)}}"><i
                                                            class="{{$child->icon}}"></i> {{$child->name}}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
