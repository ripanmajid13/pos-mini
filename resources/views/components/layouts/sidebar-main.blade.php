<aside class="main-sidebar sidebar-dark-primary elevation-1" style="background-color: #3E4551;">
    <a href="{{ url('/dashboard') }}" class="brand-link text-sm navbar-lightblue">
      <img src="{{ $logo }}" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8; width: 29px;">
      <span class="brand-text font-weight-bold">{{ $title }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-1 pb-1 mb-1 d-flex">
            <div class="image pt-1">
                <img src="{{ $userImage }}" class="img-circle elevation-2 img-profile" alt="User Image" style="height: 33px; width: 33px;">
            </div>

            <div class="info text-white py-0">
                <p class="mb-0 text-sm auth-name">{{ auth()->user()->name }}</p>
                <p class="mb-0 text-xs">{{ ucwords(implode(', ', auth()->user()->roles->pluck('name')->toArray())) }}</p>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent nav-compact flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->segment(1) == 'dashboard' ? 'active' : '' }} pb-1">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @foreach ($navigations as $navigation)
                    @canany($navigation->children->pluck('url'))
                        @if ($navigation->children->count())
                            @php $active = count(array_intersect([request()->segment(1)], json_decode($navigation->children->pluck('url')))); @endphp

                            <li class="nav-item has-treeview{{ $active ? ' menu-open' : ''}}">
                                <a href="javascripy:;" class="nav-link{{ $active ? ' active' : ''}} pb-1">
                                    <i class="nav-icon {{ $navigation->icon }}"></i>
                                    <p>{{ $navigation->name }} <i class="right fas fa-angle-left" style="top: 10px;"></i></p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @foreach ($navigation->children as $child)
                                        @can($child->url)
                                            <li class="nav-item">
                                                <a href="{{ url($child->url) }}" class="nav-link {{ request()->segment(1) == $child->url ? 'active' : '' }} pb-1">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ $child->name }}</p>
                                                </a>
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @else
                        @isset($navigation->url)
                            @can($navigation->url)
                                <li class="nav-item">
                                    <a href="{{ url($navigation->url) }}" class="nav-link {{ request()->segment(1) == $navigation->url ? 'active' : '' }} pb-1">
                                        <i class="nav-icon {{ $navigation->icon }}"></i>
                                        <p>{{ $navigation->name }}</p>
                                    </a>
                                </li>
                            @endcan
                        @endisset
                    @endcanany
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
