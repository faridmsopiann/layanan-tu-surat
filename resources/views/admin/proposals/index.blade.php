@extends('admin.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4 mb-5" style="font-family: 'Roboto', sans-serif;">
    <h2 style="font-weight: 700; color: #2C3E50;">Manajemen Proposal</h2>

    @if(session('success'))
    <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tombol Tambah Proposal (Trigger Modal) -->
    <button type="button" class="btn btn-primary mb-3 shadow-sm" data-toggle="modal" data-target="#createProposalModal" style="background-color: #3498DB; border: none; border-radius: 50px; padding: 8px 20px;">
       <i class="fas fa-plus-circle"></i> Buat Proposal Baru
    </button>

    <!-- Tabel Proposal -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <thead style="background-color: #ECF0F1; color: #2C3E50;">
                <tr>
                    <th class="text-sm">No</th>
                    <th class="text-sm">Nomor Agenda</th>
                    <th class="text-sm">File</th>
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
                <tr class="align-middle" style="transition: background-color 0.3s;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $proposal->nomor_agenda }}</td>
                    <td>
                        @if ($proposal->soft_file)
                            <a href="{{ asset('storage/' . $proposal->soft_file) }}" target="_blank" style="color: #2980B9; text-decoration: none;">
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
                    <td class="text-sm">{{ $proposal->untuk }}</td>
                    <td class="text-sm">
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
                        <span class="badge badge-pill {{ $statusColors[$proposal->status_disposisi] ?? 'badge-secondary' }}">{{ $proposal->status_disposisi }}</span>
                    </td>
                    <td class="d-flex">
                        <!-- Tombol Edit -->
                        <button class="btn btn-outline-warning btn-sm mr-1" data-toggle="modal" data-target="#editProposalModal-{{ $proposal->id }}">
                            <i class="fas fa-user-edit"></i>
                        </button>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('admin.proposals.destroy', $proposal->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit Proposal -->
                <div class="modal fade" id="editProposalModal-{{ $proposal->id }}" tabindex="-1" aria-labelledby="editProposalModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                <h5 class="modal-title" id="editProposalModalLabel">Edit Proposal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.proposals.update', $proposal->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="edit_nomor_agenda" class="form-label">Nomor Agenda</label>
                                                <input type="text" class="form-control" id="edit_nomor_agenda" name="nomor_agenda" value="{{ $proposal->nomor_agenda }}" required>
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
                                            <div class="mb-3">
                                                <label for="edit_soft_file" class="form-label">Upload Soft File (PDF)</label>
                                                <input type="file" class="form-control-file" id="edit_soft_file" name="soft_file" accept=".pdf">
                                                @if ($proposal->soft_file)
                                                    <small class="text-muted">
                                                        File yang diunggah: <a href="{{ asset('storage/' . $proposal->soft_file) }}" target="_blank">Lihat PDF yang diunggah</a>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="edit_hal" class="form-label">Hal</label>
                                                <input type="text" class="form-control" id="edit_hal" name="hal" value="{{ $proposal->hal }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_diterima_tanggal" class="form-label">Diterima Tanggal</label>
                                                <input type="date" class="form-control" id="edit_diterima_tanggal" name="diterima_tanggal" value="{{ $proposal->diterima_tanggal }}" required>
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
            @endforeach
            </tbody>
        </table>
        {!! $proposals->links('pagination::bootstrap-5') !!}
    </div>
</div>

<!-- Modal Buat Proposal Baru -->
<!-- Modal Buat Proposal Baru -->
<div class="modal fade" id="createProposalModal" tabindex="-1" aria-labelledby="createProposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                <h5 class="modal-title" id="createProposalModalLabel">Buat Proposal Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.proposals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_agenda" class="form-label">Nomor Agenda</label>
                                <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_surat" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
                            </div>
                            <div class="mb-3">
                                <label for="asal_surat" class="form-label">Asal Surat</label>
                                <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hal" class="form-label">Hal</label>
                                <input type="text" class="form-control" id="hal" name="hal" required>
                            </div>
                            <div class="mb-3">
                                <label for="diterima_tanggal" class="form-label">Diterima Tanggal</label>
                                <input type="date" class="form-control" id="diterima_tanggal" name="diterima_tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="untuk" class="form-label">Untuk</label>
                                <input type="text" class="form-control" id="untuk" name="untuk" required>
                            </div>
                            <div class="mb-3">
                                <label for="status_disposisi" class="form-label">Status Disposisi</label>
                                <select class="form-select" id="status_disposisi" name="status_disposisi" required>
                                    <option value="Memproses">Memproses</option>
                                    <option value="Menunggu Approval Dekan">Menunggu Approval Dekan</option>
                                    <option value="Menunggu Approval Kabag">Menunggu Approval Kabag</option>
                                    <option value="Menunggu Approval Keuangan">Menunggu Approval Keuangan</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="soft_file">Upload Soft File (PDF):</label>
                        <input type="file" class="form-control-file" id="soft_file" name="soft_file" required accept=".pdf">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
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