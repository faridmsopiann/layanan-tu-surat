@extends('tu.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4 mb-5">
    <h2 class="mb-4" style="font-weight: 700; color: #2C3E50;">Disposisi Proposal</h2>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Proposal -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 20px;">
            <thead class="bg-info">
                <tr>
                    <th>No</th>
                    <th class="text-sm">Nomor Agenda</th>
                    <th class="text-sm">File</th>
                    <th class="text-sm">Jenis</th>
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
                    <td>
                        @if ($proposal->soft_file)
                            <a href="{{ asset('storage/' . $proposal->soft_file) }}" style="color: #2980B9;" target="_blank">
                                <i class="fas fa-file-pdf"></i>Lihat PDF
                            </a>
                        @else
                            <span>Tidak ada</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->jenis_proposal}}</td>
                    <td class="text-sm">{{ $proposal->tanggal_surat }}</td>
                    <td class="text-sm">{{ $proposal->nomor_surat }}</td>
                    <td class="text-sm">{{ $proposal->asal_surat }}</td>
                    <td class="text-sm">{{ $proposal->hal }}</td>
                    <td class="text-sm">{{ $proposal->diterima_tanggal }}</td>
                    <td class="text-sm">
                        @if($proposal->status_disposisi == 'Memproses')
                        <span class="badge badge-pill badge-warning">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Dekan')
                            <span class="badge badge-pill badge-primary">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag')
                            <span class="badge badge-pill badge-secondary">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Keuangan')
                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Selesai')
                            <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Ditolak')
                            <span class="badge badge-pill badge-danger">{{ $proposal->status_disposisi }}</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->dari }}</td> 
                    <td class="text-sm">{{ $proposal->tujuan_disposisi }}</td> 
                    <td class="text-sm">{{ $proposal->pesan_disposisi }}</td>
                    <td class="d-flex justify-content-between align-items-center">
                        {{-- Tombol untuk membuka modal Disposisi --}}
                        @if($proposal->status_disposisi === 'Memproses')
                            <button type="button" class="btn btn-info btn-sm w-100" data-toggle="modal" data-target="#disposisiModal-{{ $proposal->id }}">
                                <i class="fas fa-share"></i> Disposisi
                            </button>
                        @elseif($proposal->status_disposisi === 'Menunggu Approval Kabag')
                            <button class="btn btn-info btn-sm mr-1 w-100" data-toggle="modal" data-target="#disposisiModal-{{ $proposal->id }}">
                                <i class="fas fa-share"></i> 
                            </button>
                            <!-- Tombol Reject -->
                            <button class="btn btn-danger btn-sm w-100" data-toggle="modal" data-target="#rejectProposalModal-{{ $proposal->id }}">
                                <i class="fas fa-times"></i> 
                            </button>
                        @elseif($proposal->status_disposisi === 'Ditolak')
                            <a href="{{ route('tu.disposisi.selesaikan', $proposal->id) }}" class="btn btn-success btn-sm d-inline-block w-100">
                                Selesaikan
                            </a>
                        @elseif($proposal->status_disposisi === 'Selesai')
                            <a href="{{ route('tu.disposisi.selesaikan', $proposal->id) }}" class="btn btn-success btn-sm d-inline-block w-100">
                                Selesaikan
                            </a>
                        @endif
                    </td>
                </tr>
    
                <!-- Modal Disposisi -->
                <div class="modal fade" id="disposisiModal-{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="disposisiModalLabel{{ $proposal->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="disposisiModalLabel{{ $proposal->id }}">Form Disposisi Proposal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('tu.disposisi.update', $proposal->id) }}">
                                    @csrf
                                    @method('PUT')
    
                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <select class="form-control" id="dari" name="dari">
                                            @if ($proposal->status_disposisi == 'Memproses')
                                                <option value="Staff Tata Usaha">Staff Tata Usaha</option>
                                            @elseif ($proposal->status_disposisi == 'Menunggu Approval Kabag')
                                                <option value="Kabag Tata Usaha">Kabag Tata Usaha</option>
                                            @endif  
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="disposisi">Pilih Tujuan Disposisi</label>
                                        <div class="dropdown-wrapper" style="position: relative;">
                                            <select id="disposisi" class="form-control" name="disposisi" required>
                                                @if($proposal->status_disposisi == 'Memproses')
                                                    <option value="Dekan">Dekan</option>
                                                    <option value="Wadek Akademik">Wadek Akademik</option>
                                                    <option value="Wadek Kemahasiswaan">Wadek Kemahasiswaan</option>
                                                    <option value="Wadek Administrasi Umum">Wadek Administrasi Umum</option>
                                                @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag' && $proposal->jenis_proposal == 'Disertai Pengajuan Dana')
                                                    <option value="Keuangan">Keuangan</option>
                                                @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag' && $proposal->jenis_proposal == 'Tanpa Pengajuan Dana')
                                                    <option value="Staff TU">Staff TU</option>
                                                @endif
                                            </select>
                                            <!-- Icon panah bawah -->
                                            <span class="dropdown-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                                                <i class="fas fa-chevron-down" style="color: #999;"></i>
                                            </span>
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="pesan_disposisi">Pesan Disposisi</label>
                                        <textarea id="pesan_disposisi" class="form-control" name="pesan_disposisi" rows="3" required>{{ old('pesan_disposisi') }}</textarea>
                                    </div>
    
                                    <button type="submit" class="btn btn-primary">Disposisi</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Modal Reject -->
                <div class="modal fade" id="rejectProposalModal-{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectProposalModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="rejectProposalModalLabel">Form Reject Proposal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Pesan Sukses -->
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
            
                                <form action="{{ route('tu.disposisi.reject.submit', $proposal->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
            
                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <select class="form-control" id="dari" name="dari" disabled>
                                            <option value="Kabag Tata Usaha">Kabag Tata Usaha</option>
                                        </select>
                                    </div>
            
                                    <div class="form-group">
                                        <label for="tujuan">Tujuan</label>
                                        <select class="form-control" id="tujuan" name="tujuan" required>
                                            <option value="Staff TU">Staff TU</option>
                                        </select>
                                    </div>
            
                                    <div class="form-group">
                                        <label for="pesan">Pesan</label>
                                        <textarea class="form-control" id="pesan" name="pesan" rows="3" required></textarea>
                                    </div>
            
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger btn-sm">Reject Proposal</button>
                                    </div>
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