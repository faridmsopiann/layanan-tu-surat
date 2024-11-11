<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('pemohon.dashboard') }}" class="brand-link logo-switch" style="display: flex; align-items: center;">
                        <!-- Logo kecil untuk sidebar collapsed -->
                        <img src="{{ asset('images/uinxs.png') }}" alt="Logo UIN XS" class="logo-xs" id="small-logo" style="height: 50px; width: auto; left: 5px;">
                        <!-- Logo besar untuk sidebar expanded -->
                        <img src="{{ asset('images/uin.png') }}" alt="Logo UIN" id="main-logo" class="logo-xl mt-3 mb-4" style="max-width: 100%; height: auto; object-fit: contain; transform: scale(1.2); left: 5px;">
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pemohon.dashboard') }}" class="nav-link {{ Request::routeIs('pemohon.dashboard') ? 'active' : '' }} mt-2">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Pengajuan Menu Item -->
                <li class="nav-item">
                    <a href="{{ route('pemohon.proposals.index') }}" class="nav-link {{ Request::routeIs('pemohon.proposals.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-upload"></i>
                        <p>Pengajuan</p>
                    </a>
                </li>

                {{-- <!-- Arsip Pengajuan Menu Item -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>Arsip Pengajuan</p>
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
