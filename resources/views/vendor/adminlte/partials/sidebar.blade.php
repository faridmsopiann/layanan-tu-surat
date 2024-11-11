<aside class="main-sidebar sidebar-dark-primary elevation-4 min-vh-100">
    {{-- <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Admin Dashboard</span>
    </a> --}}

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- User Info Section -->
                <li class="nav-item">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="info">
                            <h3 style="color: #ffffff;">Welcome, </h3>
                            <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Disposisi Menu Item -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Disposisi</p>
                    </a>
                </li>

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
