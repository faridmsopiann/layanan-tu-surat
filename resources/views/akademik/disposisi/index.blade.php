@extends('akademik.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4 mb-5">
    <h2 class="mb-4" style="font-weight: 700; color: #2C3E50;">Disposisi Surat</h2>

    
    @if(session('success'))
    <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-responsive">
        <!-- Tabel Proposal -->
        <table class="table table-bordered">
            <thead class="bg-info">
                <tr>
                    <th class="text-sm">No</th>
                    <th class="text-sm">Nomor Agenda</th>
                    <th class="text-sm">File</th>
                    <th class="text-sm">Tanggal Surat</th>
                    <th class="text-sm">Nomor Surat</th>
                    <th class="text-sm">Asal Surat</th>
                    <th class="text-sm">Hal</th>
                    <th class="text-sm">Diterima Tanggal</th>
                    <th class="text-sm">Status Disposisi</th>
                    <th class="text-sm">Dari</th>
                    <th class="text-sm">Tujuan</th>
                    <th class="text-sm">Pesan Disposisi</th>
                    <th class="text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposals as $proposal)
                <tr>
                    <td class="text-sm">{{ $loop->iteration }}</td>
                    <td class="text-sm">{{ $proposal->nomor_agenda }}</td>
                    <td class="text-sm">
                        @if ($proposal->soft_file)
                            @php
                                $files = json_decode($proposal->soft_file, true);
                            @endphp

                            @if (count($files) == 1)
                                <div class="mt-0">
                                    <a href="{{ asset('storage/' . $files[0]) }}" class="btn-sm btn-info" download>
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            @elseif (count($files) > 1)
                                <div class="mt-0">
                                    <a href="{{ route('akademik.proposals.downloadZip', $proposal->id) }}" class="btn-sm btn-info">
                                        <i class="fas fa-file-archive"></i> Download ZIP
                                    </a>
                                </div>
                            @endif
                        @endif

                        @if ($proposal->soft_file_link)
                            <div class="mt-3">
                                <p>Link Terkait Dokumen:
                                    <a href="{{ $proposal->soft_file_link }}" target="_blank">
                                        {{ $proposal->soft_file_link }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        @if (!$proposal->soft_file && !$proposal->soft_file_link)
                            <p class="text-muted">Tidak ada file atau link yang diunggah.</p>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->tanggal_surat }}</td>
                    <td class="text-sm">{{ $proposal->nomor_surat }}</td>
                    <td class="text-sm">{{ $proposal->asal_surat }}</td>
                    <td class="text-sm">{{ $proposal->hal }}</td>
                    <td class="text-sm">{{ $proposal->diterima_tanggal }}</td>
                    <td class="text-sm">
                        @if($proposal->status_disposisi == 'Menunggu Approval Akademik')
                        <span class="badge badge-primary">{{ $proposal->status_disposisi }}</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->dari }}</td>
                    <td class="text-sm">{{ $proposal->tujuan_disposisi }}</td>
                    <td class="text-sm">{{ $proposal->pesan_disposisi }}</td>
                    <td class="d-flex">
                        <a href="#" data-toggle="modal" data-target="#detailModal{{ $proposal->id }}" class="btn btn-outline-info btn-sm mr-1">
                            <i class="fas fa-eye"></i>
                        </a>

                        <div class="modal fade" id="detailModal{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $proposal->id }}" aria-hidden="true">
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
                                                <p><strong>Kode Pengajuan:</strong> {{ $proposal->kode_pengajuan }}</p>
                                                <p><strong>Nomor Agenda:</strong> {{ $proposal->nomor_agenda }}</p>
                                                <p><strong>Tanggal Surat:</strong> {{ $proposal->tanggal_surat }}</p>
                                                <p><strong>Nomor Surat:</strong> {{ $proposal->nomor_surat }}</p>
                                                <p><strong>Untuk:</strong> {{ $proposal->untuk }}</p>
                                                <p><strong>Status Terkini:</strong> 
                                                    @if($proposal->status_disposisi == 'Memproses')
                                                        <span class="badge badge-pill badge-warning">{{ $proposal->status_disposisi }}</span>
                                                    @elseif($proposal->status_disposisi == 'Menunggu Approval Dekan' || $proposal->status_disposisi == 'Menunggu Approval Wadek Akademik' || $proposal->status_disposisi == 'Menunggu Approval Wadek Kemahasiswaan' || $proposal->status_disposisi == 'Menunggu Approval Wadek Administrasi Umum')
                                                        <span class="badge badge-pill badge-primary">{{ $proposal->status_disposisi }}</span>
                                                    @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag')
                                                        <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                                                    @elseif($proposal->status_disposisi == 'Menunggu Approval Keuangan')
                                                        <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                                                    @elseif($proposal->status_disposisi == 'Selesai')
                                                        <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                                                    @elseif($proposal->status_disposisi == 'Ditolak')
                                                        <span class="badge badge-pill badge-danger">{{ $proposal->status_disposisi }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Asal Surat:</strong> {{ $proposal->asal_surat }}</p>
                                                <p><strong>Hal:</strong> {{ $proposal->hal }}</p>
                                                <p><strong>Nama Pemohon:</strong> {{ $proposal->pemohon->name }}</p>
                                                <p><strong>Diterima Tanggal:</strong> {{ $proposal->diterima_tanggal }}</p>
                                                @if ($proposal->status_disposisi == 'Selesai')
                                                    
                                                        <p class="text-success">
                                                            <strong>Selesai Dalam:</strong>
                                                            {{
                                                                \Carbon\Carbon::parse($proposal->diterima_tanggal)
                                                                    ->diff(\Carbon\Carbon::parse($proposal->updated_at))
                                                                    ->format('%d hari, %h jam, %i menit, %s detik')
                                                            }}
                                                        </p>
                                                    
                                                @elseif($proposal->status_disposisi == 'Ditolak')
                                                    <p class="text-danger">
                                                        <strong>Ditolak Dalam:</strong>
                                                        {{
                                                            \Carbon\Carbon::parse($proposal->diterima_tanggal)
                                                                ->diff(\Carbon\Carbon::parse($proposal->updated_at))
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
                                                    @foreach($proposal->modalDisposisi as $m)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $m->tujuan }}</td>
                                                        <td>
                                                            <span class="badge badge-pill badge-{{ $m->status == 'Disetujui' ? 'success' : ($m->status == 'Ditolak' ? 'danger' : 'warning') }}">
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
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Disposisi -->
                        <button class="btn btn-info btn-sm mr-1 w-100" data-toggle="modal" data-target="#disposisiModal-{{ $proposal->id }}">
                            <i class="fas fa-share"></i> 
                        </button>
                        <!-- Tombol Reject -->
                        <button class="btn btn-danger btn-sm w-100" data-toggle="modal" data-target="#rejectModal-{{ $proposal->id }}">
                            <i class="fas fa-times"></i> 
                        </button>
                    </td>
                </tr>

                <!-- Modal Disposisi -->
                <div class="modal fade" id="disposisiModal-{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="disposisiModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="disposisiModalLabel">Form Disposisi Surat</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('akademik.disposisi.updateDisposisi', $proposal->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <select class="form-control" id="dari" name="dari">
                                            <option value="Akademik">Akademik</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="disposisi">Tujuan Disposisi</label>
                                        <select id="disposisi" class="form-control" name="disposisi">
                                            <option value="Dekan">Dekan</option>
                                            <option value="Kabag TU">Kabag TU</option>
                                            <option value="Wadek Akademik">Wadek Akademik</option>
                                            <option value="Wadek Kemahasiswaan">Wadek Kemahasiswaan</option>
                                            <option value="Wadek Administrasi Umum">Wadek Administrasi Umum</option>
                                            <option value="Keuangan">Keuangan</option>
                                            <option value="Prodi">Prodi</option>
                                            <option value="PLT">PLT</option>
                                            <option value="Umum">Umum</option>
                                            <option value="Perpus">Perpus</option>
                                            <option value="Staff TU">Staff TU (Selesai)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="pesan_disposisi">Pesan Disposisi</label>
                                        <textarea id="pesan_disposisi" class="form-control" name="pesan_disposisi" rows="3" required>{{ old('pesan_disposisi') }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Disposisi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Reject -->
                <div class="modal fade" id="rejectModal-{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="rejectModalLabel">Form Reject Proposal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('akademik.disposisi.updateReject', $proposal->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <select class="form-control" id="dari" name="dari">
                                            <option value="Akademik">Akademik</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="disposisi">Tujuan Reject</label>
                                        <select id="disposisi" class="form-control" name="disposisi" disabled>
                                            <option value="Staff TU">Staff TU</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="pesan_disposisi">Pesan Reject</label>
                                        <textarea id="pesan_disposisi" class="form-control" name="pesan_disposisi" rows="3" required>{{ old('pesan_disposisi') }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
</style>
@endpush