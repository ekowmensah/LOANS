<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{url('/')}}" class="brand-link">
        @if(!empty($logo=\Modules\Setting\Entities\Setting::where('setting_key','core.company_logo')->first()->setting_value))
            <img class="brand-image img-circle elevation-3" src="{{asset('storage/uploads/'.$logo)}}"
                 srcset="{{asset('storage/uploads/'.$logo)}} 2x"
                 alt="logo">
        @else
            <span class="brand-text font-weight-light">{{\Modules\Setting\Entities\Setting::where('setting_key','core.company_name')->first()->setting_value}}</span>
        @endif
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(!empty(Auth::user()->photo))
                    <img
                            class="img-circle elevation-2"
                            src="{{asset('storage/uploads/'.Auth::user()->photo)}}"
                            alt="User Image">
                @else
                    <img class="img-circle elevation-2"
                         src="{{asset('themes/adminlte/img/user.png')}}"
                         alt="User profile picture">
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</a>
            </div>
        </div>
        
        @php
            // Check if user is a field agent by checking the field_agents table
            $isFieldAgent = \Modules\FieldAgent\Entities\FieldAgent::where('user_id', Auth::id())->exists();
        @endphp
        
        <!-- Debug indicator -->
        @if($isFieldAgent)
            <div style="background: #28a745; color: white; padding: 5px; text-align: center; font-size: 12px;">
                ✓ Field Agent Mode Active
            </div>
        @else
            <div style="background: #dc3545; color: white; padding: 5px; text-align: center; font-size: 12px;">
                ✗ Admin Mode (User ID: {{ Auth::id() }})
            </div>
        @endif
        
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                @if($isFieldAgent)
                    {{-- Field Agent Menu - Only Dashboard and Field Agent Functions --}}
                    <li class="nav-item">
                        <a href="{{url('field-agent/dashboard')}}" class="nav-link @if(Request::is('field-agent/dashboard')) active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('field-agent/my-clients')}}" class="nav-link @if(Request::is('field-agent/my-clients*')) active @endif">
                            <i class="nav-icon fas fa-users"></i>
                            <p>My Clients</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('field-agent/collection')}}" class="nav-link @if(Request::is('field-agent/collection*')) active @endif">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Collections</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('field-agent/daily-report')}}" class="nav-link @if(Request::is('field-agent/daily-report*')) active @endif">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Daily Reports</p>
                        </a>
                    </li>
                @else
                    {{-- Regular Admin Menu --}}
                    @foreach(\Modules\Core\Entities\Menu::with('children')->where('is_parent',1)->orderBy('menu_order','asc')->get() as $parent)
                    @if($parent->children->count()==0)
                        @if(!empty($parent->permissions))
                            @can($parent->permissions)
                                <li class="nav-item">
                                    <a href="{{url($parent->url)}}" class="nav-link @if(Request::is($parent->url)) active @endif">
                                        <i class="nav-icon fas {{$parent->icon}}"></i>
                                        <p>
                                            {{$parent->name}}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        @else
                            <li class="nav-item">
                                <a href="{{url($parent->url)}}" class="nav-link @if(Request::is($parent->url)) active @endif">
                                    <i class="nav-icon fas {{$parent->icon}}"></i>
                                    <p>
                                        {{$parent->name}}
                                    </p>
                                </a>
                            </li>
                        @endif
                    @else
                        @if(!empty($parent->permissions))
                            @can($parent->permissions)
                                <li class="nav-item has-treeview @if(Request::is($parent->url.'*')) menu-open @endif">
                                    <a href="#" class="nav-link @if(Request::is($parent->url.'*')) active @endif">
                                        <i class="nav-icon fas {{$parent->icon}}"></i>
                                        <p>
                                            {{$parent->name}}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @foreach($parent->children as $child)
                                            @if(!empty($child->permissions))
                                                @can($child->permissions)
                                                    <li class="nav-item">
                                                        <a href="{{url($child->url)}}" class="nav-link @if(Request::is($child->url)) active @endif">
                                                            <i class="nav-icon fas {{$child->icon}}"></i>
                                                            <p>
                                                                {{$child->name}}
                                                            </p>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @else
                                                <li class="nav-item">
                                                    <a href="{{url($child->url)}}" class="nav-link @if(Request::is($child->url)) active @endif">
                                                        <i class="nav-icon fas {{$child->icon}}"></i>
                                                        <p>
                                                            {{$child->name}}
                                                        </p>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endcan
                        @else
                            <li class="nav-item has-treeview @if(Request::is($parent->url.'*')) menu-open @endif">
                                <a href="#" class="nav-link @if(Request::is($parent->url.'*')) active @endif">
                                    <i class="nav-icon fas {{$parent->icon}}"></i>
                                    <p>
                                        {{$parent->name}}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($parent->children as $child)
                                        @if(!empty($child->permissions))
                                            @can($child->permissions)
                                                <li class="nav-item">
                                                    <a href="{{url($child->url)}}" class="nav-link @if(Request::is($child->url)) active @endif">
                                                        <i class="nav-icon fas {{$child->icon}}"></i>
                                                        <p>
                                                            {{$child->name}}
                                                        </p>
                                                    </a>
                                                </li>
                                            @endcan
                                        @else
                                            <li class="nav-item">
                                                <a  href="{{url($child->url)}}" class="nav-link @if(Request::is($child->url)) active @endif">
                                                    <i class="nav-icon fas {{$child->icon}}"></i>
                                                    <p>
                                                        {{$child->name}}
                                                    </p>
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
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
