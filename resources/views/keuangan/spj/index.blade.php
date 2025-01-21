@extends('keuangan.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4 mb-5">
    <h2 class="mb-4" style="font-weight: 700; color: #2C3E50;">Pengajuan SPJ</h2>


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
                    <th class="text-sm">Pemohon</th>
                    <th class="text-sm">Kategori</th>
                    <th class="text-sm">Tanggal Pengajuan</th>
                    <th class="text-sm">Tanggal Selesai</th>
                    <th class="text-sm">Status</th>
                    <th class="text-sm">Catatan</th>
                    <th class="text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spjs as $spj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $spj->user->name }} <br> <small>{{ $spj->user->email }}</small></td>
                    <td>{{ $spj->jenis }} <br>
                        <small>
                            <a href="{{ asset('storage/' . $spj->proposal->soft_file) }}" target="_blank" style="color: #2980B9; text-decoration: none;">
                                <i class="fas fa-file-pdf"></i> Lihat File
                            </a>
                        </small>
                    </td>
                    <td>{{ $spj->tanggal_proses ? \Carbon\Carbon::parse($spj->tanggal_proses)->format('d F Y H:i'): '-' }}</td>
                    <td>{{ $spj->tanggal_selesai ? \Carbon\Carbon::parse($spj->tanggal_selesai)->format('d F Y H:i') : '-' }}</td>
                    <td>
                        @if($spj->status == 'Pending')
                        <span class="badge badge-warning">Diproses</span>
                        @elseif($spj->status == 'Revisi')
                        <span class="badge badge-danger">Revisi</span>
                        @elseif($spj->status == 'Selesai')
                        <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($spj->status == 'Selesai')
                        Mohon kirimkan hard file ke divisi Keuangan
                        @else
                        {{ $spj->catatan ?? '-' }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('keuangan.spj.show', $spj->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-share"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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