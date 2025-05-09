@extends('tu.layouts.app')

@section('content')
<div class="container pb-4 mb-4" style="font-family: 'Roboto', sans-serif;">
    <h2 class="pt-4" style="font-weight: 700; color: #2C3E50;">Arsip Surat</h2>

    @if($arsipProposals->isEmpty())
        <form method="GET" action="{{ route('tu.arsip.index') }}" class="p-2 rounded shadow-sm bg-info">
            <input type="text" name="no_agenda" placeholder="No Agenda" value="{{ request('no_agenda') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari No Agenda">
            <input type="date" name="tanggal_surat" value="{{ request('tanggal_surat') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari Tanggal Surat">
            <input type="text" name="asal_surat" placeholder="Asal Surat" value="{{ request('asal_surat') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari Asal Surat">
            <input type="text" name="perihal" placeholder="Perihal" value="{{ request('perihal') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari Perihal">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('tu.arsip.index') }}" class="btn btn-secondary btn-sm mr-1">
                <i class="fas fa-undo-alt"></i>
            </a>
        </form>
        <p class="text-center mt-4">Tidak ada proposal.</p>
    @else
        <form method="GET" action="{{ route('tu.arsip.index') }}" class="p-2 rounded shadow-sm bg-info">
            <input type="text" name="no_agenda" placeholder="No Agenda" value="{{ request('no_agenda') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari No Agenda">
            <input type="date" name="tanggal_surat" value="{{ request('tanggal_surat') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari Tanggal Surat">
            <input type="text" name="asal_surat" placeholder="Asal Surat" value="{{ request('asal_surat') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari Asal Surat">
            <input type="text" name="perihal" placeholder="Perihal" value="{{ request('perihal') }}" class="form-control form-control-sm d-inline w-auto mr-1 mb-1" aria-label="Cari Perihal">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('tu.arsip.index') }}" class="btn btn-secondary btn-sm mr-1">
                <i class="fas fa-undo-alt"></i>
            </a>
        </form>

        @if(session('success'))
            <div id="success-alert" class="alert alert-success mb-3 shadow-sm " style="border-left: 5px solid #28a745;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
                <thead style="background-color: #ECF0F1; color: #2C3E50;">
                    <tr>
                        <th class="text-sm">No</th>
                        <th class="text-sm">No Agenda</th>
                        <th class="text-sm">Tanggal Surat</th>
                        <th class="text-sm">Asal Surat</th>
                        <th class="text-sm">Perihal</th>
                        <th class="text-sm">Jenis Surat</th>
                        <th class="text-sm">File PDF</th>
                        <th class="text-sm">Status</th>
                        <th class="text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arsipProposals as $key => $proposal)
                        <tr class="align-middle" style="transition: background-color 0.3s;">
                            <td class="text-sm">{{ $key + 1 }}</td>
                            <td class="text-sm">{{ $proposal->nomor_agenda }}</td>
                            <td class="text-sm">{{ $proposal->tanggal_surat }}</td>
                            <td class="text-sm">{{ $proposal->asal_surat }}</td>
                            <td class="text-sm">{{ $proposal->hal }}</td>
                            <td class="text-sm">{{ $proposal->jenis_proposal }}</td>
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

                                                   <span class="badge badge-pill {{ $statusColors[$proposal->status_disposisi] ?? 'badge-secondary' }}">{{ $proposal->status_disposisi }}</span>
                            </td>
                            <td class="d-flex">
                                <!-- Detail Button and Modal -->
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

                                <!-- Delete Button -->
                                <form action="{{ route('tu.arsip.destroy', $proposal->id) }}" method="POST" style="display:inline;">
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
            {!! $arsipProposals->links('pagination::bootstrap-5') !!}
        </div>
    @endif
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