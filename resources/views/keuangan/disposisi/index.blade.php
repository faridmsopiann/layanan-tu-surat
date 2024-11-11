@extends('keuangan.layouts.app')

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
                        <a href="{{ asset('storage/' . $proposal->soft_file) }}" target="_blank" style="color: #2980B9;">
                            <i class="fas fa-file-pdf"></i> Lihat PDF
                        </a>
                        @else
                            <span style="color: #7f8c8d;">Tidak ada</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->tanggal_surat }}</td>
                    <td class="text-sm">{{ $proposal->nomor_surat }}</td>
                    <td class="text-sm">{{ $proposal->asal_surat }}</td>
                    <td class="text-sm">{{ $proposal->hal }}</td>
                    <td class="text-sm">{{ $proposal->diterima_tanggal }}</td>
                    <td class="text-sm">
                        @if($proposal->status_disposisi == 'Memproses')
                        <span class="badge badge-warning">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Dekan')
                            <span class="badge badge-primary">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag')
                            <span class="badge badge-success">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Keuangan')
                            <span class="badge badge-info">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Selesai')
                            <span class="badge badge-success">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Ditolak')
                            <span class="badge badge-danger">{{ $proposal->status_disposisi }}</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->dari }}</td>
                    <td class="text-sm">{{ $proposal->tujuan_disposisi }}</td>
                    <td class="text-sm">{{ $proposal->pesan_disposisi }}</td>
                    <td class="d-flex">
                        <!-- Tombol Disposisi -->
                        <button type="button" class="btn btn-info btn-sm d-inline-block mr-1 w-100" data-toggle="modal" data-target="#disposisiModal{{ $proposal->id }}">
                            <i class="fas fa-share"></i> 
                        </button>
                        <!-- Tombol Reject -->
                        <button type="button" class="btn btn-danger btn-sm d-inline-block w-100" data-toggle="modal" data-target="#rejectModal{{ $proposal->id }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
    
                <!-- Modal Disposisi -->
                <div class="modal fade" id="disposisiModal{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="disposisiModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="disposisiModalLabel">Form Disposisi Proposal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('keuangan.disposisi.updateDisposisi', $proposal->id) }}">
                                    @csrf
                                    @method('PUT')
    
                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <select class="form-control" id="dari" name="dari">
                                            <option value="Keuangan">Keuangan</option>
                                        </select>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="disposisi">Tujuan Disposisi</label>
                                        <select id="disposisi" class="form-control" name="disposisi" disabled>
                                            <option value="Staff TU">Staff Tata Usaha</option>
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
                <div class="modal fade" id="rejectModal{{ $proposal->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="rejectModalLabel">Form Reject Proposal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('keuangan.disposisi.updateReject', $proposal->id) }}">
                                    @csrf
                                    @method('PUT')
    
                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <select class="form-control" id="dari" name="dari">
                                            <option value="Keuangan">Keuangan</option>
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