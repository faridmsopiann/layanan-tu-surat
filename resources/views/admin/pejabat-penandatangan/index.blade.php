@extends('admin.layouts.app')

@section('title', 'Manajemen Pejabat Penandatangan')

@section('content')
<div class="container pt-4" style="font-family: 'Roboto', sans-serif;">
    <h2 style="font-weight: 700; color: #2C3E50;">Manajemen Pejabat Penandatangan</h2>

    @if(session('success'))
    <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3 shadow-sm" style="background-color: #3498DB; border: none; border-radius: 50px; padding: 8px 20px;" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus-circle"></i> Tambah Pejabat
    </button>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <thead style="background-color: #ECF0F1; color: #2C3E50;">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>TTD</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->nip }}</td>
                    <td>{{ $item->jabatan->nama ?? '-' }}</td>
                    <td>
                        @if($item->ttd)
                            <img src="{{ asset($item->ttd) }}" alt="TTD" style="height: 50px;">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td class="d-flex justify-content-center">
                        <!-- Edit -->
                        <button class="btn btn-outline-warning btn-sm mr-2" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <!-- Delete -->
                        <form action="{{ route('admin.pejabat-penandatangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" type="submit">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('admin.pejabat-penandatangan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                                    <h5 class="modal-title">Edit Pejabat</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>NIP</label>
                                        <input type="text" name="nip" class="form-control" value="{{ $item->nip }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <select name="jabatan_id" class="form-control" required>
                                            <option value="">- Pilih Jabatan -</option>
                                            @foreach($jabatans as $jab)
                                            <option value="{{ $jab->id }}" {{ $item->jabatan_id == $jab->id ? 'selected' : '' }}>
                                                {{ $jab->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Upload TTD Baru (Opsional)</label>
                                        <input type="file" name="ttd" class="form-control-file">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.pejabat-penandatangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                    <h5 class="modal-title">Tambah Pejabat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <select name="jabatan_id" class="form-control" required>
                            <option value="">- Pilih Jabatan -</option>
                            @foreach($jabatans as $jab)
                            <option value="{{ $jab->id }}">{{ $jab->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload TTD (Opsional)</label>
                        <input type="file" name="ttd" class="form-control-file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById("success-alert");
        if (alert) {
            setTimeout(() => {
                alert.classList.add("fade-out");
                setTimeout(() => alert.remove(), 500);
            }, 1000);
        }
    });
</script>
<style>
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
</style>
@endpush
