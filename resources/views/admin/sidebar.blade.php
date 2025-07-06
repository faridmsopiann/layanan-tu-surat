<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Logo -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="brand-link logo-switch">
                        <img src="{{ asset('images/uinxs.png') }}" alt="Logo UIN XS" class="logo-xs" id="small-logo">
                        <img src="{{ asset('images/uin.png') }}" alt="Logo UIN" id="main-logo" class="logo-xl mt-3 mb-4">
                    </a>
                </li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }} mt-2">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MASTER USERS -->
                <li class="nav-item has-treeview {{ Request::is('admin/users*') || Request::is('roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('admin/users*') || Request::is('roles*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Master Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::routeIs('roles.*') ? 'active' : '' }}">
                                <i class="fas fa-address-book nav-icon"></i>
                                <p>Manajemen Roles</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MASTER SURAT TUGAS -->
                <li class="nav-item has-treeview {{ Request::is('admin/jenis-kegiatan*') || Request::is('admin/instansi*') || Request::is('admin/peran-tugas*') || Request::is('admin/unit-kerja*') || Request::is('admin/pegawai-penugasan*') || Request::is('admin/proposals*') || Request::routeIs('admin.kop-surat.*') || Request::routeIs('admin.pejabat-penandatangan.*') || Request::routeIs('admin.jabatan.*')  ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('admin/jenis-kegiatan*') || Request::is('admin/instansi*') || Request::is('admin/peran-tugas*') || Request::is('admin/unit-kerja*') || Request::is('admin/pegawai-penugasan*') || Request::is('admin/proposals*') || Request::routeIs('admin.kop-surat.*') || Request::routeIs('admin.pejabat-penandatangan.*') || Request::routeIs('admin.jabatan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Master Surat
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.proposals.index') }}" class="nav-link {{ Request::routeIs('admin.proposals.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt nav-icon"></i>
                                <p>Manajemen Surat</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.jenis-kegiatan.index') }}" class="nav-link {{ Request::routeIs('admin.jenis-kegiatan.*') ? 'active' : '' }}">
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>Jenis Kegiatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.jabatan.index') }}" class="nav-link {{ Request::routeIs('admin.jabatan.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.instansi.index') }}" class="nav-link {{ Request::routeIs('admin.instansi.*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Instansi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.peran-tugas.index') }}" class="nav-link {{ Request::routeIs('admin.peran-tugas.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>Peran Tugas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.unit-kerja.index') }}" class="nav-link {{ Request::routeIs('admin.unit-kerja.*') ? 'active' : '' }}">
                                <i class="fas fa-sitemap nav-icon"></i>
                                <p>Unit Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.pegawai-penugasan.index') }}" class="nav-link {{ Request::routeIs('admin.pegawai-penugasan.*') ? 'active' : '' }}">
                                <i class="fas fa-chalkboard-teacher nav-icon"></i>
                                <p>Pegawai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.kop-surat.index') }}" class="nav-link {{ Request::routeIs('admin.kop-surat.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt nav-icon"></i>
                                <p>Kop Surat</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.pejabat-penandatangan.index') }}" class="nav-link {{ Request::routeIs('admin.pejabat-penandatangan.*') ? 'active' : '' }}">
                                <i class="fas fa-user-edit nav-icon"></i>
                                <p>Pejabat Penandatangan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Logout -->
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
