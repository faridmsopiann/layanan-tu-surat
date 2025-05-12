<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')</title>
    <link rel="icon" href="{{ asset('images/uinxs.png') }}" type="image/png">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @livewireStyles
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        h4 {
            margin-top: 30px;
            margin-bottom: 20px;
            color: #2C3E50;
            text-align: center;
        }

        .card-tracking {
            border: none;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .badge-pill {
            font-size: 13px;
            padding: 6px 12px;
        }

        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-center {
            text-align: center;
        }

        .btn-logout {
            float: right;
            margin-top: -40px;
        }

        .terms {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h4>Pelacakan Status Surat Anda</h4>

        <!-- Logout Button -->
        <a href="{{ route('logout') }}" class="btn btn-danger btn-sm btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt"></i> Keluar
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <div class="card card-tracking">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Surat</th>
                            <th>Asal Surat</th>
                            <th>Hal</th>
                            <th>Status</th>
                            <th>Pengajuan</th>
                            <th>Surat Keluar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $statusColors = [
                                'Memproses' => 'badge-warning',
                                'Menunggu Approval Dekan' => 'badge-primary',
                                'Menunggu Approval Kabag' => 'badge-success',
                                'Menunggu Approval Keuangan' => 'badge-info',
                                'Selesai' => 'badge-success',
                                'Ditolak' => 'badge-danger',
                            ];
                        @endphp

                        @foreach($proposals as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->tanggal_surat }}</td>
                            <td>{{ $p->asal_surat }}</td>
                            <td>{{ $p->hal }}</td>
                            <td>
                                <span class="badge badge-pill {{ $statusColors[$p->status_disposisi] ?? 'badge-secondary' }}">
                                    {{ $p->status_disposisi }}
                                </span>
                            </td>
                            <td>
                                @php $files = json_decode($p->soft_file, true) ?? []; @endphp
                                @if(count($files) == 1)
                                    <a href="{{ asset('storage/' . $files[0]) }}" class="btn btn-sm btn-info" download>
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                @elseif(count($files) > 1)
                                    <a href="{{ route('pemohon.proposals.downloadZip', $p->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-file-archive"></i> ZIP
                                    </a>
                                @endif
                                @if ($p->soft_file_link)
                                    <div class="mt-1 small">
                                        <a href="{{ $p->soft_file_link }}" target="_blank">Link Dokumen</a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($p->soft_file_sk)
                                    <a href="{{ asset('storage/' . $p->soft_file_sk) }}" class="btn btn-sm btn-success" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    <span class="text-muted">Tidak Ada</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#detailModal{{ $p->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document" style="max-width: 70%; margin: 40px auto 0;">
                                        <div class="modal-content shadow-sm">
                                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                                <h5 class="modal-title">Detail Surat</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="font-size: 15px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Kode Pengajuan:</strong> {{ $p->kode_pengajuan }}</p>
                                                        <p><strong>Nomor Agenda:</strong> {{ $p->nomor_agenda }}</p>
                                                        <p><strong>Tanggal Surat:</strong> {{ $p->tanggal_surat }}</p>
                                                        <p><strong>Nomor Surat:</strong> {{ $p->nomor_surat }}</p>
                                                        <p><strong>Untuk:</strong> {{ $p->untuk }}</p>
                                                        <p><strong>Status Terkini:</strong>
                                                            @if($p->status_disposisi == 'Memproses')
                                                                <span class="badge badge-pill badge-warning">{{ $p->status_disposisi }}</span>
                                                            @elseif($p->status_disposisi == 'Menunggu Approval Dekan' || $p->status_disposisi == 'Menunggu Approval Wadek Akademik' || $p->status_disposisi == 'Menunggu Approval Wadek Kemahasiswaan' || $p->status_disposisi == 'Menunggu Approval Wadek Administrasi Umum')
                                                                <span class="badge badge-pill badge-primary">{{ $p->status_disposisi }}</span>
                                                            @elseif($p->status_disposisi == 'Menunggu Approval Kabag')
                                                                <span class="badge badge-pill badge-success">{{ $p->status_disposisi }}</span>
                                                            @elseif($p->status_disposisi == 'Menunggu Approval Keuangan')
                                                                <span class="badge badge-pill badge-info">{{ $p->status_disposisi }}</span>
                                                            @elseif($p->status_disposisi == 'Selesai')
                                                                <span class="badge badge-pill badge-success">{{ $p->status_disposisi }}</span>
                                                            @elseif($p->status_disposisi == 'Ditolak')
                                                                <span class="badge badge-pill badge-danger">{{ $p->status_disposisi }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Asal Surat:</strong> {{ $p->asal_surat }}</p>
                                                        <p><strong>Hal:</strong> {{ $p->hal }}</p>
                                                        <p><strong>Nama Pemohon:</strong> {{ $p->pemohon->name }}</p>
                                                        <p><strong>Diterima Tanggal:</strong> {{ $p->diterima_tanggal }}</p>
                                                        @if ($p->status_disposisi == 'Selesai')
                                                            <p class="text-success">
                                                                <strong>Selesai Dalam:</strong>
                                                                {{
                                                                    \Carbon\Carbon::parse($p->diterima_tanggal)
                                                                        ->diff(\Carbon\Carbon::parse($p->updated_at))
                                                                        ->format('%d hari, %h jam, %i menit, %s detik')
                                                                }}
                                                            </p>
                                                        @elseif($p->status_disposisi == 'Ditolak')
                                                            <p class="text-danger">
                                                                <strong>Ditolak Dalam:</strong>
                                                                {{
                                                                    \Carbon\Carbon::parse($p->diterima_tanggal)
                                                                        ->diff(\Carbon\Carbon::parse($p->updated_at))
                                                                        ->format('%d hari, %h jam, %i menit, %s detik')
                                                                }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h5 class="mt-4">Detail Disposisi:</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tujuan</th>
                                                                <th>Status</th>
                                                                <th>Tanggal Diterima</th>
                                                                <th>Tanggal Proses</th>
                                                                <th>Diverifikasi Oleh</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($p->modalDisposisi as $m)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $m->tujuan }}</td>
                                                                <td>
                                                                    <span class="badge badge-pill badge-{{ $m->status == 'Disetujui' ? 'success' : ($m->status == 'Ditolak' ? 'danger' : 'warning') }}" data-toggle="tooltip" title="Status: {{ $m->status }}">
                                                                        {{ $m->status }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($m->tanggal_diterima)->format('Y-m-d H:i:s') }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($m->tanggal_proses)->format('Y-m-d H:i:s') }}</td>
                                                                <td>{{ $m->diverifikasi_oleh }}</td>
                                                                <td>{{ $m->keterangan }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {!! $proposals->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Section -->
    <div class="terms pl-5 pr-5">
        <hr>
        <p><strong>Terms and Conditions</strong></p>
        <p>Sistem ini disediakan untuk memfasilitasi pemantauan status surat secara daring bagi sivitas Fakultas Sains dan Teknologi. 
           Dengan menggunakan sistem ini, Anda setuju untuk mematuhi seluruh kebijakan keamanan dan kerahasiaan informasi. 
           Segala bentuk penyalahgunaan data atau akses yang tidak sah akan dikenakan sanksi sesuai ketentuan yang berlaku.</p>
        <p>&copy; {{ date('Y') }} Fakultas Sains dan Teknologi, UIN Syarif Hidayatullah Jakarta</p>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
