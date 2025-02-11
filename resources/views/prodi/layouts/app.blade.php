<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')</title>
    <!-- Tambahkan favicon untuk menampilkan logo -->
    <link rel="icon" href="{{ asset('images/uinxs.png') }}" type="image/png">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    @livewireStyles
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <!-- Hamburger Menu Icon -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Welcome Message -->
                <li class="nav-item mr-3">
                    <span class="navbar-text">
                        <strong>Selamat Datang,</strong> {{ auth()->user()->name }}
                    </span>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        @include('prodi.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        {{-- <!-- Main Footer -->
        @include('adminlte::partials.footer') --}}
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @livewireScripts

    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Memuat jQuery (hanya jika Anda menggunakan Bootstrap 4 atau lebih rendah) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Memuat Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

     <!-- Script tambahan lainnya -->
     @stack('scripts')
</body>
</html>
