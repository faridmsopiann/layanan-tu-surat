@extends('pemohon.layouts.app')

@section('content')
<div class="container pb-4" style="font-family: 'Roboto', sans-serif;">
    <h1 class="pt-4" style="font-weight: 700; color: #2C3E50;">Pengajuan Surat Masuk</h1>

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
                        <div class="form-group">
                            <label for="hal"><strong>Hal</strong></label>
                            <input type="text" name="hal" id="hal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="soft_file"><strong>Upload Soft File</strong></label>
                            <input type="file" name="soft_file[]" id="soft_file" class="form-control-file" multiple>
                        </div>                        
                        <div class="form-group">
                            <label for="file_link"><strong>Atau Masukkan Link</strong> (jika tidak ingin upload file)</label>
                            <input type="url" name="file_link" id="file_link" class="form-control" placeholder="Masukkan URL file">
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
                    <th>Pengajuan</th>
                    <th>Surat Keluar</th>
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
                        @if($p->status_disposisi == 'Memproses')
                            <span class="badge badge-pill badge-warning">{{ $p->status_disposisi }}</span>
                        @elseif($p->status_disposisi == 'Selesai')
                            <span class="badge badge-pill badge-success">{{ $p->status_disposisi }}</span>
                        @elseif($p->status_disposisi == 'Ditolak')
                            <span class="badge badge-pill badge-danger">{{ $p->status_disposisi }}</span>
                        @else
                            <span class="badge badge-pill badge-primary">{{ $p->status_disposisi }}</span>
                        @endif
                    </td>

                    <td>
                        @if ($p->soft_file)
                            @php
                                $files = json_decode($p->soft_file, true) ?? []; 
                            @endphp

                            @if (count($files) == 1)
                                <div class="mt-0">
                                    <a href="{{ asset('storage/' . $files[0]) }}" class="btn-sm btn-info" style="white-space: nowrap;" download>
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            @elseif (count($files) > 1)
                                <div class="mt-0">
                                    <a href="{{ route('pemohon.proposals.downloadZip', $p->id) }}" class="btn-sm btn-info" style="white-space: nowrap;">
                                        <i class="fas fa-file-archive"></i> Download ZIP
                                    </a>
                                </div>
                            @endif
                        @endif

                        @if ($p->soft_file_link)
                            <div class="mt-3">
                                <p>Link Terkait Dokumen:
                                    <a href="{{ $p->soft_file_link }}" target="_blank">
                                        {{ $p->soft_file_link }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        @if (!$p->soft_file && !$p->soft_file_link)
                            <p class="text-muted">Tidak ada file atau link yang diunggah.</p>
                        @endif
                    </td>
                    <td class="text-sm">
                        @if ($p->soft_file_sk)
                            <a href="{{ asset('storage/' . $p->soft_file_sk) }}" class="btn-sm btn-success" style="white-space: nowrap;" download>
                                <i class="fas fa-download"></i> 
                            </a>
                        @else
                            <span class="text-muted">Tidak Ada/Belum diunggah</span>
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
                                                    @elseif($p->status_disposisi == 'Selesai')
                                                        <span class="badge badge-pill badge-success">{{ $p->status_disposisi }}</span>
                                                    @elseif($p->status_disposisi == 'Ditolak')
                                                        <span class="badge badge-pill badge-danger">{{ $p->status_disposisi }}</span>
                                                    @else
                                                        <span class="badge badge-pill badge-info">{{ $p->status_disposisi }}</span>
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
                                        @if ($p->status_disposisi == 'Selesai')
                                            <a href="{{ route('pemohon.proposals.pdf', $p->id) }}" target="_blank" class="btn btn-primary">
                                                <i class="fas fa-print"></i> Cetak PDF
                                            </a>
                                            <a href="{{ route('pemohon.proposals.word', $p->id) }}" class="btn btn-info" target="_blank">
                                                <i class="fas fa-file-word"></i> Cetak Word
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button Edit -->
                        <a href="#" data-toggle="modal" data-target="#editModal{{ $p->id }}" class="btn btn-outline-warning btn-sm mr-1">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Modal Edit Proposal -->
                        <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content shadow-sm">
                                    <div class="modal-header" style="background-color: #FFC107; color: black;">
                                        <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Edit Proposal</h5>
                                        <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('pemohon.proposals.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body" style="font-size: 15px;">
                                            <div class="form-group">
                                                <label for="tanggal_surat"><strong>Tanggal Surat</strong></label>
                                                <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" value="{{ $p->tanggal_surat }}" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="hal"><strong>Hal</strong></label>
                                                <input type="text" name="hal" id="hal" class="form-control" value="{{ $p->hal }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="soft_file"><strong>Upload Soft File</strong></label>
                                                <input type="file" name="soft_file[]" id="soft_file" class="form-control-file" multiple>
                                                <small class="text-muted">File sebelumnya: 
                                                    @if ($p->soft_file)
                                                        @php
                                                            $files = json_decode($p->soft_file, true); // Pastikan ini array
                                                        @endphp
                                                
                                                        @if (count($files) == 1)
                                                            <a href="{{ asset('storage/'.$files[0]) }}" target="_blank">📂 Lihat File</a>
                                                        @elseif (count($files) > 1)
                                                            <a href="{{ route('pemohon.proposals.downloadZip', $p->id) }}">📁 Download ZIP</a>
                                                        @endif
                                                    @else
                                                        Tidak ada file
                                                    @endif
                                                </small>                                                
                                            </div>                        
                                            <div class="form-group">
                                                <label for="file_link"><strong>Atau Masukkan Link</strong> (jika tidak ingin upload file)</label>
                                                <input type="url" name="file_link" id="file_link" class="form-control" placeholder="Masukkan URL file" value="{{ $p->soft_file_link }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
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