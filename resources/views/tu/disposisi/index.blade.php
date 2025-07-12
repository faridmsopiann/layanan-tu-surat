@extends('tu.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4 mb-5">
    <h2 class="mb-4" style="font-weight: 700; color: #2C3E50;">Disposisi Surat</h2>

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
                    <th class="text-sm">File Surat Masuk</th>
                    <th class="text-sm">Jenis</th>
                    <th class="text-sm">Tanggal Surat</th>
                    <th class="text-sm">Nomor Surat</th>
                    <th class="text-sm">Asal Surat</th>
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
                            @php
                                $files = json_decode($proposal->soft_file, true);
                            @endphp

                            @if (count($files) == 1)
                                <div class="mt-0">
                                    <a href="{{ asset('storage/' . $files[0]) }}" class="btn-sm btn-info" style="white-space: nowrap;" download>
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            @elseif (count($files) > 1)
                                <div class="mt-0">
                                    <a href="{{ route('tu.proposals.downloadZip', $proposal->id) }}" class="btn-sm btn-info" style="white-space: nowrap;">
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
                    <td class="text-sm">{{ $proposal->jenis_proposal}}</td>
                    <td class="text-sm">{{ $proposal->tanggal_surat }}</td>
                    <td class="text-sm">{{ $proposal->nomor_surat }}</td>
                    <td class="text-sm">{{ $proposal->asal_surat }}</td>
                    <td class="text-sm">{{ $proposal->diterima_tanggal }}</td>
                    <td class="text-sm">
                        @if($proposal->status_disposisi == 'Memproses')
                            <span class="badge badge-pill badge-warning">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Selesai')
                            <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                        @elseif($proposal->status_disposisi == 'Ditolak')
                            <span class="badge badge-pill badge-danger">{{ $proposal->status_disposisi }}</span>
                        @else
                            <span class="badge badge-pill badge-primary">{{ $proposal->status_disposisi }}</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $proposal->dari }}</td> 
                    <td class="text-sm">{{ $proposal->tujuan_disposisi }}</td> 
                    <td class="text-sm">{{ $proposal->pesan_disposisi }}</td>
                    <td class="d-flex justify-content-between align-items-center">
                        {{-- Tombol untuk membuka modal Disposisi --}}
                        @if($proposal->status_disposisi === 'Memproses')
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
                                                        @elseif($proposal->status_disposisi == 'Selesai')
                                                            <span class="badge badge-pill badge-success">{{ $proposal->status_disposisi }}</span>
                                                        @elseif($proposal->status_disposisi == 'Ditolak')
                                                            <span class="badge badge-pill badge-danger">{{ $proposal->status_disposisi }}</span>
                                                        @else
                                                            <span class="badge badge-pill badge-info">{{ $proposal->status_disposisi }}</span>
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
                                                    @if ($proposal->perlu_sk)
                                                    <div class="form-group">
                                                            <label>File Surat Keluar:</label><br>
                                                            @if ($proposal->soft_file_sk)
                                                                <a href="{{ asset('storage/' . $proposal->soft_file_sk) }}"
                                                                class="btn btn-sm btn-success" style="white-space: nowrap;" download>
                                                                    <i class="fas fa-download"></i> Download SK
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Belum diunggah</span>
                                                            @endif
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($proposal->jenis_proposal === 'Surat Tugas')
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><strong>Jenis Kegiatan</strong></label>
                                                            <input type="text" class="form-control" value="{{ $proposal->jenisKegiatan->nama ?? '-' }}" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                            <label><strong>Tanggal Mulai</strong></label>
                                                            <input type="text" class="form-control" value="{{ $proposal->tanggal_mulai }}" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                            <label><strong>Tanggal Selesai</strong></label>
                                                            <input type="text" class="form-control" value="{{ $proposal->tanggal_selesai }}" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                            <label><strong>Lokasi Kegiatan</strong></label>
                                                            <input type="text" class="form-control" value="{{ $proposal->lokasi_kegiatan }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><strong>Instansi Terkait</strong></label>
                                                            @php
                                                                $instansiTerkait = $proposal->instansi->pluck('instansi.nama')->filter()->implode(', ');
                                                                $instansiManual = $proposal->instansi->pluck('nama_manual')->filter()->implode(', ');
                                                                $instansiGabung = trim($instansiTerkait . ($instansiTerkait && $instansiManual ? ', ' : '') . $instansiManual);
                                                            @endphp
                                                            <textarea class="form-control" rows="2" readonly>{{ $instansiGabung ?: '-' }}</textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label><strong>Soft File</strong></label><br>
                                                            @if ($proposal->soft_file)
                                                                @php $files = json_decode($proposal->soft_file, true); @endphp
                                                                <div class="d-flex flex-wrap">
                                                                    @foreach ($files as $file)
                                                                        <a href="{{ asset('storage/'.$file) }}" target="_blank" class="badge badge-primary mb-2 mr-2">
                                                                            📂 Lihat File
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @elseif ($proposal->soft_file_link)
                                                                <a href="{{ $proposal->soft_file_link }}" target="_blank">{{ $proposal->soft_file_link }}</a>
                                                            @else
                                                                <p class="text-muted">Tidak ada file.</p>
                                                            @endif
                                                        </div>

                                                        <div class="form-group">
                                                            <label><strong>Daftar Penugasan</strong></label>
                                                            @forelse ($proposal->penugasan as $p)
                                                                <p class="mb-1">
                                                                    <strong>{{ $p->pegawaiPenugasan->nama ?? $p->nama_manual }}</strong><br>
                                                                    <small>Peran: {{ $p->peranTugas->nama ?? '-' }}</small><br>
                                                                    <small>Unit: {{ $p->unit_asal }}</small>
                                                                </p>
                                                            @empty
                                                                <p class="text-muted">Belum ada penugasan.</p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

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

                            <!-- Cek Perlu Surat Keluar -->
                            @if($proposal->perlu_sk && $proposal->pihak_pembuat_sk == $proposal->tujuan_disposisi)
                                <!-- Tombol Upload -->
                                <button class="btn btn-warning btn-sm mr-1" onclick="event.preventDefault(); document.getElementById('upload-sk-input-{{ $proposal->id }}').click();">
                                    <i class="fas fa-upload"></i>
                                </button>
                            
                                <!-- Input file tersembunyi -->
                                <form id="upload-sk-form-{{ $proposal->id }}" action="{{ route('tu.proposal.upload-sk', $proposal->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                                    @csrf
                                    <input type="file" name="soft_file_sk" id="upload-sk-input-{{ $proposal->id }}" accept="application/pdf" onchange="document.getElementById('upload-sk-form-{{ $proposal->id }}').submit();">
                                </form>
                            @endif

                            <button type="button" class="btn btn-info btn-sm w-100" data-toggle="modal" data-target="#disposisiModal-{{ $proposal->id }}">
                                <i class="fas fa-share"></i> 
                            </button>
                        @elseif($proposal->status_disposisi === 'Menunggu Approval Kabag')
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

                             <!-- Cek Perlu Surat Keluar -->
                            @if($proposal->perlu_sk && $proposal->pihak_pembuat_sk == 'Kabag TU')
                                <!-- Tombol Upload -->
                                <button class="btn btn-warning btn-sm mr-1" onclick="event.preventDefault(); document.getElementById('upload-sk-input-{{ $proposal->id }}').click();">
                                    <i class="fas fa-upload"></i>
                                </button>
                            
                                <!-- Input file tersembunyi -->
                                <form id="upload-sk-form-{{ $proposal->id }}" action="{{ route('tu.proposal.upload-sk', $proposal->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                                    @csrf
                                    <input type="file" name="soft_file_sk" id="upload-sk-input-{{ $proposal->id }}" accept="application/pdf" onchange="document.getElementById('upload-sk-form-{{ $proposal->id }}').submit();">
                                </form>
                            @endif

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
                                <h5 class="modal-title" id="disposisiModalLabel{{ $proposal->id }}">Form Disposisi Surat</h5>
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
                                                    @foreach(json_decode($proposal->pihak_ttd, true) as $item)
                                                            <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag')
                                                   @foreach(json_decode($proposal->pihak_ttd, true) as $item)
                                                        <option value="{{ $item === 'Kabag TU' ? 'Staff TU' : $item }}">
                                                            {{ $item === 'Kabag TU' ? '🟢 Kembalikan ke Staff TU (Selesai)' : $item }}
                                                        </option>
                                                    @endforeach
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    @foreach ($proposals as $proposal)
        const containerId = "pdf-canvas-{{ $proposal->id }}";
        const url = "{{ asset('storage/' . $proposal->soft_file_sk) }}";

        pdfjsLib.getDocument(url).promise.then(function (pdf) {
            let container = document.getElementById(containerId);
            container.innerHTML = "";
            container.style.overflowX = "auto";

            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                pdf.getPage(pageNum).then(function (page) {
                    let viewport = page.getViewport({ scale: 1.5 });
                    let canvas = document.createElement('canvas');
                    let context = canvas.getContext('2d');

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.style.border = "1px solid #ccc";
                    canvas.style.display = "block";
                    canvas.style.margin = "0 auto 10px"; // tengah + bawah

                    canvas.dataset.page = page.pageNumber;
                    container.appendChild(canvas);

                    page.render({ canvasContext: context, viewport: viewport });

                    let isDrawing = false, lastX = 0, lastY = 0;

                    canvas.addEventListener('mousedown', (e) => {
                        isDrawing = true;
                        lastX = e.offsetX;
                        lastY = e.offsetY;
                    });

                    canvas.addEventListener('mousemove', (e) => {
                        if (!isDrawing) return;
                        context.beginPath();
                        context.moveTo(lastX, lastY);
                        context.lineTo(e.offsetX, e.offsetY);
                        context.strokeStyle = '#000';
                        context.lineWidth = 2;
                        context.lineJoin = 'round';
                        context.stroke();
                        lastX = e.offsetX;
                        lastY = e.offsetY;
                    });

                    canvas.addEventListener('mouseup', () => isDrawing = false);
                    canvas.addEventListener('mouseleave', () => isDrawing = false);

                    // Simpan tanda tangan
                    canvas.addEventListener("click", function (e) {
                        let rect = canvas.getBoundingClientRect();
                        let x = (e.clientX - rect.left) * (canvas.width / rect.width);
                        let y = (e.clientY - rect.top) * (canvas.height / rect.height);

                        const form = document.getElementById("sign-form-{{ $proposal->id }}");
                        form.querySelector("input[name='x']").value = x;
                        form.querySelector("input[name='y']").value = y;
                        form.querySelector("input[name='page']").value = canvas.dataset.page;
                        form.querySelector("input[name='signature']").value = canvas.toDataURL("image/png");
                    });
                });
            }
        });
    @endforeach
});
</script>

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