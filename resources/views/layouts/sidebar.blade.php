   <div class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/images/easemart-logo.png" alt="AdminLTE Logo" class="brand-image elevation-2">
            <span class="brand-text">EaseMart</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div> --}}


            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false" id="sidebar">
                    @if(!empty($groupModule))
                    @foreach($groupModule as $group)
                        @if(isset($group->children) && !empty($group->children))
                           <li class="nav-item menu">
                                <a href="#" class="nav-link">
                                    <p>
                                        {{ $group->group_module_name}}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                @foreach($group->children as $module)
                                    <li class="nav-item">
                                    @if(request()->is("/") || $module->uri == "")
                                        <a href="/{{ $module->uri }}" class="nav-link {{ request()->is("/".$module->uri) ? 'active' : '' }}">
                                    @else
                                        <a href="/{{ $module->uri }}" class="nav-link {{ request()->is($module->uri.'*') ? 'active' : '' }}">
                                    @endif
                                        <i class="nav-icon {{ $module->icon}}"></i>
                                        <p>{{ $module->module_name}}</p>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @elseif(!isset($group->children))
                            <li class="nav-item">
                                @if(request()->is("/") || $group->uri == "")
                                <a href="/{{ $group->uri }}" class="nav-link {{ request()->is("/".$group->uri) ? 'active' : '' }}">
                                @else
                                <a href="/{{ $group->uri }}" class="nav-link {{ request()->is($group->uri.'*') ? 'active' : '' }}">
                                @endif
                                <i class="nav-icon {{ $group->icon}}"></i>
                                <p>{{ $group->module_name}}</p>
                                </a>
                            </li>
                        @endif
                    @endforeach
                    @endif
                </ul>
            </nav>

            <!-- /.sidebar-menu -->
        </div>
    </div>
