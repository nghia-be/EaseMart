<aside id="menu" class="sidebar sidebar">
    <ul class="nav metis-menu" id="side-menu">
        <li class="tw-mt-[63px] sm:tw-mt-0 -tw-mx-2 tw-overflow-hidden sm:tw-bg-neutral-900/50">
            <div id="logo" class="tw-py-2 tw-px-2 tw-h-[63px] tw-flex tw-items-center">
                <a href="{{ url('/') }}" class="!tw-mt-0 logo logo-text">
                    <img
                        style="max-width: 100%; height: auto;line-height: .8;margin: -3px 0.5rem 0px 0.8rem;max-height: 33px;"
                        src="{{ asset('themes/images/easemart-logo.png') }}" alt="Logo"
                        class="">
                    
                </a>
            </div>
        </li>
        @php
            $icon = ['fa-user', 'fa-repeat', 'fa-life-ring', 'fa-cog', 'fa-chart-bar', 'fa-cogs', 'fa-cogs', 'fa-cogs'];
            $key = 0;
        @endphp
        @if(!empty($groupModule))
            @foreach($groupModule as $group)
                <li class="menu-item">
                    <a href="#" aria-expanded="false">
                        <i class="fa {{ $icon[$key] ?? null }} menu-icon"></i>
                        <span class="menu-text">{{ $group->group_module_name}}</span>
                        <span class="fa arrow pleft5"></span>
                    </a>
                    @if(!empty($group->children))
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            @foreach($group->children as $module)
                                <li class="sub-menu-item-{{ $module->module_name}}">
                                    <a href="{{ url($module->uri) }}">
                                        <i class="fa {{ $module->icon}} menu-icon"></i>
                                        <span class="sub-menu-text">{{ $module->module_name}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
                @php
                    $key ++;
                @endphp
            @endforeach
        @endif
    </ul>
</aside>
