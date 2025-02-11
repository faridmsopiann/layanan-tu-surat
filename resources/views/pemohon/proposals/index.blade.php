@extends('pemohon.layouts.app')

@section('content')
<div class="container pb-4" style="font-family: 'Roboto', sans-serif;">
    <h1 class="mt-4" style="font-weight: 700; color: #2C3E50;">Pengajuan Surat</h1>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tombol Ajukan Proposal Baru -->
    <button type="button" class="btn btn-primary mb-3 shadow-sm" data-toggle="modal" data-target="#createModal" style="background-color: #3498DB; border: none; border-radius: 50px; padding: 8px 20px;">
        <i class="fas fa-plus-circle"></i> Ajukan Surat Baru
    </button>

    <!-- Modal Create Proposal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-sm">
                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                    <h5 class="modal-title" id="createModalLabel">Ajukan Surat Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pemohon.proposals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="font-size: 15px;">
                        <div class="form-group">
                            <label for="tanggal_surat"><strong>Tanggal Surat</strong></label>
                            <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="asal_surat"><strong>Asal Surat</strong></label>
                            <input type="text" name="asal_surat" id="asal_surat" class="form-control" required>
                        </div>
                        {{-- <div class="form-group">
                            <label for="jenis_proposal"><strong>Jenis Surat</strong></label>
                            <div class="dropdown-wrapper" style="position: relative;">
                                <select name="jenis_proposal" id="jenis_proposal" class="form-control" required style="appearance: none; padding-right: 30px;">
                                    <option value="Disertai Pengajuan Dana">Disertai Pengajuan Dana</option>
                                    <option value="Tanpa Pengajuan Dana">Tanpa Pengajuan Dana</option>
                                </select>
                                <!-- Icon panah bawah -->
                                <span class="dropdown-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                                    <i class="fas fa-chevron-down" style="color: #999;"></i>
                                </span>
                            </div>
                        </div>                         --}}
                        <div class="form-group">
                            <label for="hal"><strong>Hal</strong></label>
                            <input type="text" name="hal" id="hal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="soft_file"><strong>Upload Soft File (PDF)</strong></label>
                            <input type="file" name="soft_file" id="soft_file" class="form-control-file" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Proposal -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <thead style="background-color: #ECF0F1; color: #2C3E50;">
                <tr>
                    <th>No</th>
                    <th>Tanggal Surat</th>
                    <th>Asal Surat</th>
                    {{-- <th>Jenis</th> --}}
                    <th>Hal</th>
                    <th>Status</th>
                    <th>Soft File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposals as $p)
                <tr class="align-middle" style="transition: background-color 0.3s;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->tanggal_surat }}</td>
                    <td>{{ $p->asal_surat }}</td>
                    {{-- <td>{{ $p->jenis_proposal }}</td> --}}
                    <td>{{ $p->hal }}</td>
                    <td>
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
                        <span class="badge badge-pill {{ $statusColors[$p->status_disposisi] ?? 'badge-secondary' }}">{{ $p->status_disposisi }}</span>
                    </td>

                    <td>
                        @if ($p->soft_file)
                            <a href="{{ asset('storage/' . $p->soft_file) }}" target="_blank" style="color: #2980B9; text-decoration: none;">
                                <i class="fas fa-file-pdf"></i> Lihat PDF
                            </a>
                        @else
                            <span style="color: #7f8c8d;">Tidak ada</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        <!-- Button Detail -->
                        <a href="#" data-toggle="modal" data-target="#detailModal{{ $p->id }}" class="btn btn-outline-info btn-sm mr-1">
                            <i class="fas fa-eye"></i>
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

                        <!-- Button Alasan Penolakan (Jika ditolak) -->
                        @if ($p->status_disposisi == 'Ditolak' || $p->status_disposisi == 'Selesai' && $p->alasan_penolakan)
                        <a href="#" data-toggle="modal" data-target="#alasanModal{{ $p->id }}" class="btn btn-outline-warning btn-sm mr-1">
                            <i class="fas fa-exclamation-circle"></i>
                        </a>
                        @endif

                        <!-- Modal Alasan Penolakan -->
                        <div class="modal fade" id="alasanModal{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="alasanModalLabel{{ $p->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-center" role="document">
                                <div class="modal-content shadow-sm">
                                    <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                        <h5 class="modal-title" id="alasanModalLabel{{ $p->id }}">
                                            {{ $p->status_disposisi == 'Selesai' ? 'Pesan Tindak Lanjut Proposal' : 'Alasan Penolakan Proposal' }}
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="font-size: 15px;">
                                        <p><strong>{{ $p->status_disposisi == 'Selesai' ? 'Pesan Tindak Lanjut:' : 'Alasan Penolakan:' }}</strong></p>
                                        <p>{{ $p->alasan_penolakan }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <form action="{{ route('pemohon.proposals.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus surat ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $proposals->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successAlert = document.getElementById("success-alert");

        if (successAlert) {
            setTimeout(function() {
                successAlert.classList.add("fade-out");
                setTimeout(function() {
                    successAlert.remove();
                }, 500); // Hapus elemen setelah animasi selesai
            }, 1000);
        }
    });
</script>

<!-- Tambahkan CSS untuk transisi -->
<style>
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .modal-center {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 90vh;
    }
    .modal-dialog {
        margin: 0;
    }
</style>
@endpush