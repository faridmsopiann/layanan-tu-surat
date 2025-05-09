@extends('tu.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4 mb-5">
    <h2 class="mb-4" style="font-weight: 700; color: #2C3E50;">Pengajuan Surat</h2>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tombol Tambah Proposal -->
    {{-- <a href="{{ route('tu.proposals.create') }}" class="btn btn-primary mb-3">Buat Proposal Baru</a> --}}

    <!-- Tabel Proposal -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <thead class="bg-info text-light">
                <tr>
                    <th class="text-sm">No</th>
                    <th class="text-sm">Nama Pemohon</th>
                    <th class="text-sm">File</th>
                    <th class="text-sm">Nomor Agenda</th>
                    <th class="text-sm">Jenis</th>
                    <th class="text-sm">Tanggal Surat</th>
                    <th class="text-sm">Nomor Surat</th>
                    <th class="text-sm">Asal Surat</th>
                    <th class="text-sm">Hal</th>
                    <th class="text-sm">Diterima Tanggal</th>
                    <th class="text-sm">Untuk</th>
                    <th class="text-sm">Status Disposisi</th>
                    <th class="text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposals as $proposal)
                <tr>
                    <td class="text-sm">{{ $loop->iteration }}</td>
                    <td class="text-sm">{{ $proposal->pemohon->name }}</td>
                    <td>
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
                                    <a href="{{ route('tu.proposals.downloadZip', $proposal->id) }}" class="btn-sm btn-info">
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
                    <td class="text-sm">{{ $proposal->nomor_agenda }}</td>
                    <td class="text-sm">{{ $proposal->jenis_proposal }}</td>
                    <td class="text-sm">{{ $proposal->tanggal_surat }}</td>
                    <td class="text-sm">{{ $proposal->nomor_surat }}</td>
                    <td class="text-sm">{{ $proposal->asal_surat }}</td>
                    <td class="text-sm">{{ $proposal->hal }}</td>
                    <td class="text-sm">{{ $proposal->diterima_tanggal }}</td>
                    <td class="text-sm">{{ $proposal->untuk }}</td>
                    <td class="text-sm">
                        @if($proposal->status_disposisi == 'Memproses')
                        <span class="badge badge-pill badge-warning">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Dekan' || $proposal->status_disposisi == 'Menunggu Approval Wadek Akademik' || $proposal->status_disposisi == 'Menunggu Approval Wadek Kemahasiswaan' || $proposal->status_disposisi == 'Menunggu Approval Wadek Administrasi Umum')
                            <span class="badge badge-pill badge-primary">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag')
                            <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Prodi')
                            <span class="badge badge-pill badge-primary">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Keuangan')
                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval PLT')
                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Akademik')
                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Umum')
                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Perpus')
                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Selesai')
                            <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Ditolak')
                            <span class="badge badge-pill badge-danger">{{ $proposal->status_disposisi }}</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        <a href="#" data-toggle="modal" data-target="#detailModal{{ $proposal->id }}" class="btn btn-outline-info btn-sm mr-1">
                            <i class="fas fa-eye"></i>
                        </a>
    
                        <!-- Modal untuk Detail -->
                        <div class="modal fade" id="detailModal{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $proposal->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document"  style="max-width: 70%; margin: 40px auto 0;">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                        <h5 class="modal-title">Detail Surat</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
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
                                        {{-- <div class="row">
                                            <div class="col-md-6">
                                                
                                            </div>
                                            <div class="col-md-6">
                                                
                                               
                                            </div>
                                        </div> --}}
    
                                        <!-- Tabel Detail Disposisi -->
                                        <h5>Detail Disposisi:</h5>
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
                        
                        @if($proposal->status_disposisi !== 'Ditolak' && $proposal->status_disposisi !== 'Selesai')
                            <!-- Tombol Edit -->
                            <button class="btn btn-outline-warning btn-sm mr-1" data-toggle="modal" data-target="#editProposalModal-{{ $proposal->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                        @endif
    
                        <!-- Modal Edit Proposal -->
                        <div class="modal fade" id="editProposalModal-{{ $proposal->id }}" tabindex="-1" aria-labelledby="editProposalModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #FFC107; color: white;">
                                        <h5 class="modal-title" id="editProposalModalLabel">Edit Proposal</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('tu.proposals.update', $proposal->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_nomor_agenda" class="form-label">Nomor Agenda</label>
                                                        <input type="text" class="form-control" id="edit_nomor_agenda" name="nomor_agenda" value="{{ $proposal->nomor_agenda }}" readonly required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_tanggal_surat" class="form-label">Tanggal Surat</label>
                                                        <input type="date" class="form-control" id="edit_tanggal_surat" name="tanggal_surat" value="{{ $proposal->tanggal_surat }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_nomor_surat" class="form-label">Nomor Surat</label>
                                                        <input type="text" class="form-control" id="edit_nomor_surat" name="nomor_surat" value="{{ $proposal->nomor_surat }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_asal_surat" class="form-label">Asal Surat</label>
                                                        <input type="text" class="form-control" id="edit_asal_surat" name="asal_surat" value="{{ $proposal->asal_surat }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="jenis_surat"><strong>Jenis Surat</strong></label>
                                                        <div class="dropdown-wrapper" style="position: relative;">
                                                            <select name="jenis_surat" id="jenis_surat" class="form-control" required style="appearance: none; padding-right: 30px;">
                                                                <option value="Surat Pembayaran">Surat Pembayaran</option>
                                                                <option value="Surat Masuk">Surat Masuk</option>
                                                            </select>
                                                            <!-- Icon panah bawah -->
                                                            <span class="dropdown-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                                                                <i class="fas fa-chevron-down" style="color: #999;"></i>
                                                            </span>
                                                        </div>
                                                    </div>                        
                                                </div>
    
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="edit_hal" class="form-label">Hal</label>
                                                        <input type="text" class="form-control" id="edit_hal" name="hal" value="{{ $proposal->hal }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_diterima_tanggal" class="form-label">Diterima Tanggal</label>
                                                        <input type="datetime-local" class="form-control" id="edit_diterima_tanggal" name="diterima_tanggal" value="{{ \Carbon\Carbon::parse($proposal->diterima_tanggal)->format('Y-m-d\TH:i') }}" required>
                                                    </div>                                                    
                                                    <div class="mb-3">
                                                        <label for="edit_untuk" class="form-label">Untuk</label>
                                                        <input type="text" class="form-control" id="edit_untuk" name="untuk" value="{{ $proposal->untuk }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_status_disposisi" class="form-label">Status Disposisi</label>
                                                        <select class="form-select" id="edit_status_disposisi" name="status_disposisi" required>
                                                            <option value="Memproses" {{ $proposal->status_disposisi == 'Memproses' ? 'selected' : '' }}>Memproses</option>
                                                            <option value="Menunggu Approval Dekan" {{ $proposal->status_disposisi == 'Menunggu Approval Dekan' ? 'selected' : '' }}>Menunggu Approval Dekan</option>
                                                            <option value="Menunggu Approval Wadek Akademik" {{ $proposal->status_disposisi == 'Menunggu Approval Wadek Akademik' ? 'selected' : '' }}>Menunggu Approval Wadek Akademik</option>
                                                            <option value="Menunggu Approval Kabag" {{ $proposal->status_disposisi == 'Menunggu Approval Kabag' ? 'selected' : '' }}>Menunggu Approval Kabag</option>
                                                            <option value="Menunggu Approval Keuangan" {{ $proposal->status_disposisi == 'Menunggu Approval Keuangan' ? 'selected' : '' }}>Menunggu Approval Keuangan</option>
                                                            <option value="Selesai" {{ $proposal->status_disposisi == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                            <option value="Ditolak" {{ $proposal->status_disposisi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit Proposal -->

                        <!-- Icon action Edit -->
                        @if($proposal->status_disposisi == 'Ditolak' || $proposal->status_disposisi == 'Selesai')
                        <button class="btn btn-outline-success btn-sm mr-1" data-toggle="modal" data-target="#feedbackProposalModal-{{ $proposal->id }}">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        @endif

                        <!-- Modal untuk penolakan -->
                        <div class="modal fade" id="feedbackProposalModal-{{ $proposal->id }}" tabindex="-1" aria-labelledby="rejectProposalModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                        <h5 class="modal-title" id="editProposalModalLabel">
                                            {{ $proposal->status_disposisi == 'Ditolak' ? 'Alasan Penolakan' : 'Pesan Tindak Lanjut' }}
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('tu.proposals.reject', $proposal->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') 
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="edit_message" class="form-label">
                                                    {{ $proposal->status_disposisi == 'Ditolak' ? 'Alasan Penolakan' : 'Pesan Tindak Lanjut' }}
                                                </label>
                                                <textarea class="form-control" name="alasan_penolakan" id="alasan_penolakan" rows="4" required>{{ $proposal->alasan_penolakan ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn {{ $proposal->status_disposisi == 'Selesai' ? 'btn-success' : 'btn-danger' }}">
                                                {{ $proposal->status_disposisi == 'Selesai' ? 'Kirim' : 'Kirim' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
    
                        <!-- Tombol Hapus -->
                        <form action="{{ route('tu.proposals.destroy', $proposal->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
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
</style>
@endpush