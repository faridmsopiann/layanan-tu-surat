<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('dekan.dashboard') }}" class="brand-link logo-switch">
                        <!-- Logo kecil untuk sidebar collapsed -->
                        <img src="{{ asset('images/uinxs.png') }}" alt="Logo UIN XS" class="logo-xs" id="small-logo">
                        <!-- Logo besar untuk sidebar expanded -->
                        <img src="{{ asset('images/uin.png') }}" alt="Logo UIN" id="main-logo" class="logo-xl mt-3 mb-4">
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dekan.dashboard') }}" class="nav-link {{ Request::routeIs('dekan.dashboard') ? 'active' : '' }} mt-2">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Disposisi Menu Item -->
                <li class="nav-item">
                    <a href="{{ route('disposisi.index') }}" class="nav-link {{ Request::routeIs('disposisi.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Disposisi</p>
                    </a>
                </li>

                 {{-- <!-- Monitoring Menu Item -->
                 <li class="nav-item">
                    <a href="{{ route('monitoring.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>Monitoring</p>
                    </a>
                </li> --}}

                <li class="nav-item">
                    {{-- <a href="{{ route('admin.manage-users') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manage Users</p>
                    </a> --}}
                </li>

                <li class="nav-item">
                    {{-- <a href="{{ route('admin.proposals') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Manage Proposals</p>
                    </a> --}}
                </li>

                 <!-- Logout Button -->
                 <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
