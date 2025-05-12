<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('prodi-sistem-informasi.dashboard') }}" class="brand-link logo-switch">
                        <!-- Logo kecil untuk sidebar collapsed -->
                        <img src="{{ asset('images/uinxs.png') }}" alt="Logo UIN XS" class="logo-xs" id="small-logo">
                        <!-- Logo besar untuk sidebar expanded -->
                        <img src="{{ asset('images/uin.png') }}" alt="Logo UIN" id="main-logo" class="logo-xl mt-3 mb-4">
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('prodi-sistem-informasi.dashboard') }}" class="nav-link {{ Request::routeIs('prodi-sistem-informasi.dashboard') ? 'active' : '' }} mt-2">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Disposisi Menu Item -->
                <li class="nav-item">
                    <a href="{{ route('prodi-sistem-informasi.disposisi.index') }}" class="nav-link {{ Request::routeIs('prodi-sistem-informasi.disposisi.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Disposisi</p>
                    </a>
                </li>

                <!-- Monitoring Menu Item -->
                 <li class="nav-item">
                    <a href="{{ route('prodi-sistem-informasi.monitoring.index') }}" class="nav-link {{ Request::routeIs('prodi-sistem-informasi.monitoring.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>Monitoring</p>
                    </a>
                </li>

                <!-- Arsip Surat -->
                <li class="nav-item">
                    <a href="{{ route('prodi-sistem-informasi.arsip.index') }}" class="nav-link {{ Request::routeIs('prodi-sistem-informasi.arsip.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>Arsip Surat</p>
                    </a>
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
