<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('prodi-teknik-informatika.dashboard') }}" class="brand-link logo-switch">
                        <!-- Logo kecil untuk sidebar collapsed -->
                        <img src="{{ asset('images/uinxs.png') }}" alt="Logo UIN XS" class="logo-xs" id="small-logo">
                        <!-- Logo besar untuk sidebar expanded -->
                        <img src="{{ asset('images/uin.png') }}" alt="Logo UIN" id="main-logo" class="logo-xl mt-3 mb-4">
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('prodi-teknik-informatika.dashboard') }}" class="nav-link {{ Request::routeIs('prodi-teknik-informatika.dashboard') ? 'active' : '' }} mt-2">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Disposisi Menu Item -->
                <li class="nav-item">
                    <a href="{{ route('prodi-teknik-informatika.disposisi.index') }}" class="nav-link {{ Request::routeIs('prodi-teknik-informatika.disposisi.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Disposisi</p>
                    </a>
                </li>

                 <!-- Monitoring Menu Item -->
                 <li class="nav-item">
                    <a href="{{ route('prodi-teknik-informatika.monitoring.index') }}" class="nav-link {{ Request::routeIs('prodi-teknik-informatika.monitoring.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>Monitoring</p>
                    </a>
                </li>

                <!-- Arsip Surat -->
                <li class="nav-item">
                    <a href="{{ route('prodi-teknik-informatika.arsip.index') }}" class="nav-link {{ Request::routeIs('prodi-teknik-informatika.arsip.index') ? 'active' : '' }}">
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
