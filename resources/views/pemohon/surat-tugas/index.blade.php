@extends('pemohon.layouts.app')

@section('content')
<div class="container pb-4" style="font-family: 'Roboto', sans-serif;">
    <h1 class="pt-4" style="font-weight: 700; color: #2C3E50;">Pengajuan Surat Tugas</h1>

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

    <!-- Tombol Ajukan Surat Tugas -->
    <button type="button" class="btn btn-primary mb-3 shadow-sm" data-toggle="modal" data-target="#createModal" style="background-color: #3498DB; border: none; border-radius: 50px; padding: 8px 20px;">
        <i class="fas fa-plus-circle"></i> Ajukan Surat Tugas
    </button>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-sm">
                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                    <h5 class="modal-title">Ajukan Surat Tugas</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('pemohon.surat-tugas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>Jenis Kegiatan</strong></label>
                                <select name="jenis_kegiatan_id" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenisKegiatanList as $jk)
                                    <option value="{{ $jk->id }}">{{ $jk->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Perihal</strong></label>
                                <input type="text" name="hal" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label><strong>Tanggal Mulai</strong></label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Tanggal Selesai</strong></label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label><strong>Lokasi Kegiatan</strong></label>
                                <input type="text" name="lokasi_kegiatan" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Asal Surat</strong></label>
                                <input type="text" name="asal_surat" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label><strong>Instansi Terkait</strong></label>
                            <select name="instansi_ids[]" id="instansi-select" class="form-control select2" multiple>
                                @foreach ($instansiList as $i)
                                <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-instansi-manual">+ Tambah Instansi Manual</button>
                            <div id="instansi-manual-wrapper" class="mt-2"></div>
                        </div>

                        <div class="mt-3">
                            <label><strong>Upload Soft File</strong></label>
                            <input type="file" name="soft_file[]" class="form-control-file" multiple>
                        </div>

                        <div class="mt-3">
                            <label><strong>Atau Masukkan Link</strong></label>
                            <input type="url" name="file_link" class="form-control" placeholder="https://example.com/file">
                        </div>

                        <div class="mt-3">
                            <label><strong>Daftar Penugasan</strong></label>
                            <div id="penugasan-wrapper"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-penugasan">+ Tambah Penugasan</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Tugas -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Kegiatan</th>
                    <th>Perihal</th>
                    <th>Periode</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Pengajuan</th>
                    <th>Surat Keluar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratTugasList as $st)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $st->jenisKegiatan->nama }}</td>
                    <td>{{ $st->hal }}</td>
                    <td>{{ $st->tanggal_mulai }} s/d {{ $st->tanggal_selesai }}</td>
                    <td>{{ $st->lokasi_kegiatan }}</td>
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
                        <span class="badge badge-pill {{ $statusColors[$st->status_disposisi] ?? 'badge-secondary' }}">{{ $st->status_disposisi }}</span>
                    </td>
                    <td>
                        @if ($st->soft_file)
                            @php
                                $files = json_decode($st->soft_file, true) ?? []; 
                            @endphp

                            @if (count($files) == 1)
                                <div class="mt-0">
                                    <a href="{{ asset('storage/' . $files[0]) }}" class="btn-sm btn-info" style="white-space: nowrap;" download>
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            @elseif (count($files) > 1)
                                <div class="mt-0">
                                    <a href="{{ route('pemohon.proposals.downloadZip', $st->id) }}" class="btn-sm btn-info" style="white-space: nowrap;">
                                        <i class="fas fa-file-archive"></i> Download ZIP
                                    </a>
                                </div>
                            @endif
                        @endif

                        @if ($st->soft_file_link)
                            <div class="mt-3">
                                <p>Link Terkait Dokumen:
                                    <a href="{{ $st->soft_file_link }}" target="_blank">
                                        {{ $st->soft_file_link }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        @if (!$st->soft_file && !$st->soft_file_link)
                            <p class="text-muted">Tidak ada file atau link yang diunggah.</p>
                        @endif
                    </td>
                    <td class="text-sm">
                        @if ($st->soft_file_sk)
                            <a href="{{ asset('storage/' . $st->soft_file_sk) }}" class="btn-sm btn-success" style="white-space: nowrap;" download>
                                <i class="fas fa-download"></i> 
                            </a>
                        @else
                            <span class="text-muted">Tidak Ada/Belum diunggah</span>
                        @endif
                    </td>  
                    <td>
                        <div class="d-inline-flex gap-1 align-items-center">
                            <button data-toggle="modal" data-target="#detailModal{{ $st->id }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i>
                            </button>
                            <button data-toggle="modal" data-target="#editModal{{ $st->id }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                            </button>
                            @if ($st->status_disposisi == 'Ditolak' || ($st->status_disposisi == 'Selesai' && $st->alasan_penolakan))
                            <button data-toggle="modal" data-target="#alasanModal{{ $st->id }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-exclamation-circle"></i>
                            </button>
                            @endif
                            <form action="{{ route('pemohon.surat-tugas.destroy', $st->id) }}" method="POST" class="d-inline m-0 p-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $suratTugasList->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
@foreach ($suratTugasList as $st)
    <!-- Modal Edit Surat Tugas -->
    <div class="modal fade" id="editModal{{ $st->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $st->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content shadow-sm">
                <form action="{{ route('pemohon.surat-tugas.update', $st->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                        <div class="modal-header" style="background-color: #FFC107;">
                        <h5 class="modal-title" id="editModalLabel{{ $st->id }}">Edit Surat Tugas</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body" style="font-size: 15px;">

                    <div class="form-group">
                        <label><strong>Jenis Kegiatan</strong></label>
                        <select name="jenis_kegiatan_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach ($jenisKegiatanList as $jk)
                            <option value="{{ $jk->id }}" {{ $st->jenis_kegiatan_id == $jk->id ? 'selected' : '' }}>
                            {{ $jk->nama }}
                            </option>
                        @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label><strong>Perihal</strong></label>
                        <input type="text" name="hal" class="form-control" value="{{ $st->hal }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label><strong>Tanggal Mulai</strong></label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $st->tanggal_mulai }}" required>
                        </div>
                        <div class="form-group col-md-6">
                        <label><strong>Tanggal Selesai</strong></label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $st->tanggal_selesai }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Lokasi Kegiatan</strong></label>
                        <input type="text" name="lokasi_kegiatan" class="form-control" value="{{ $st->lokasi_kegiatan }}" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Asal Surat</strong></label>
                        <input type="text" name="asal_surat" class="form-control" value="{{ $st->asal_surat }}" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Instansi Terkait</strong></label>
                        <select name="instansi_ids[]" id="instansi-select-{{ $st->id }}" class="form-control select2" multiple>
                        @foreach ($instansiList as $i)
                            <option value="{{ $i->id }}"
                            {{ in_array($i->id, $st->instansi->pluck('instansi_id')->filter()->toArray()) ? 'selected' : '' }}>
                            {{ $i->nama }}
                            </option>
                        @endforeach
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-instansi-manual" data-id="{{ $st->id }}">
                        + Tambah Instansi Manual
                        </button>

                        <div id="instansi-manual-wrapper-{{ $st->id }}" class="mt-2">
                        @foreach ($st->instansi as $instansi)
                            @if ($instansi->nama_manual)
                            <div class="input-group mt-2 instansi-manual-item">
                                <input type="text" name="instansi_manual[]" class="form-control"
                                value="{{ $instansi->nama_manual }}" placeholder="Nama Instansi Manual">
                                <div class="input-group-append">
                                <button class="btn btn-danger remove-instansi-manual" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Upload Soft File</strong> (kosongkan jika tidak diubah)</label>
                        <input type="file" name="soft_file[]" class="form-control-file" multiple>
                        <small class="text-muted">
                        File sebelumnya:
                        @if ($st->soft_file)
                            @php $files = json_decode($st->soft_file, true); @endphp
                            @if (count($files) == 1)
                            <a href="{{ asset('storage/'.$files[0]) }}" target="_blank">📂 Lihat File</a>
                            @elseif (count($files) > 1)
                            <a href="{{ route('pemohon.proposals.downloadZip', $st->id) }}">📁 Download ZIP</a>
                            @endif
                        @else
                            Tidak ada file
                        @endif
                        </small>
                    </div>

                    <div class="form-group">
                        <label><strong>Atau Masukkan Link</strong></label>
                        <input type="url" name="file_link" class="form-control"
                        value="{{ $st->soft_file_link }}" placeholder="https://example.com/file">
                    </div>

                    <div class="form-group">
                        <label><strong>Daftar Penugasan</strong></label>
                        <div id="penugasan-wrapper-{{ $st->id }}" data-nextIndex="{{ count($st->penugasan) }}">
                        @foreach ($st->penugasan as $idx => $pen)
                            <div class="border p-3 mb-2 penugasan-item" data-index="{{ $idx }}">
                            <div class="row">
                                @if ($pen->dosen_id)
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{ $pen->dosen->nama }}">
                                    <input type="hidden" name="penugasan[{{ $idx }}][dosen_id]" value="{{ $pen->dosen_id }}">
                                </div>
                                @else
                                <div class="col-md-4">
                                    <input type="text" name="penugasan[{{ $idx }}][nama_manual]" class="form-control"
                                    value="{{ $pen->nama_manual }}" placeholder="Nama Dosen Manual" required>
                                </div>
                                @endif

                                <div class="col-md-4">
                                <select name="penugasan[{{ $idx }}][peran_tugas_id]" class="form-control" required>
                                    <option value="">-- Pilih Peran --</option>
                                    @foreach ($peranList as $pr)
                                    <option value="{{ $pr->id }}" {{ $pen->peran_tugas_id == $pr->id ? 'selected' : '' }}>
                                        {{ $pr->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                </div>

                                <div class="col-md-4">
                                <input type="text" name="penugasan[{{ $idx }}][unit_asal]" class="form-control"
                                    value="{{ $pen->unit_asal }}" placeholder="Unit Asal" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-penugasan mt-2">Hapus</button>
                            </div>
                        @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-penugasan-edit" data-id="{{ $st->id }}">
                        + Tambah Penugasan
                        </button>
                    </div>

                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal{{ $st->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $st->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 70%; margin: 40px auto 0;">

            <div class="modal-content shadow-sm">
                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                    <h5 class="modal-title">Detail Surat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 15px;">

                    <!-- Ringkasan Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Kode Pengajuan:</strong> {{ $st->kode_pengajuan }}</p>
                            <p><strong>Nomor Agenda:</strong> {{ $st->nomor_agenda }}</p>
                            <p><strong>Tanggal Surat:</strong> {{ $st->tanggal_surat }}</p>
                            <p><strong>Nomor Surat:</strong> {{ $st->nomor_surat }}</p>
                            <p><strong>Untuk:</strong> {{ $st->untuk }}</p>
                            <p><strong>Status Terkini:</strong> 
                                @if($st->status_disposisi == 'Memproses')
                                    <span class="badge badge-pill badge-warning">{{ $st->status_disposisi }}</span>
                                @elseif(Str::startsWith($st->status_disposisi, 'Menunggu Approval'))
                                    <span class="badge badge-pill badge-primary">{{ $st->status_disposisi }}</span>
                                @elseif($st->status_disposisi == 'Selesai')
                                    <span class="badge badge-pill badge-success">{{ $st->status_disposisi }}</span>
                                @elseif($st->status_disposisi == 'Ditolak')
                                    <span class="badge badge-pill badge-danger">{{ $st->status_disposisi }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Asal Surat:</strong> {{ $st->asal_surat }}</p>
                            <p><strong>Hal:</strong> {{ $st->hal }}</p>
                            <p><strong>Nama Pemohon:</strong> {{ $st->pemohon->name }}</p>
                            <p><strong>Diterima Tanggal:</strong> {{ $st->diterima_tanggal }}</p>
                            @if ($st->status_disposisi == 'Selesai')
                                <p class="text-success">
                                    <strong>Selesai Dalam:</strong>
                                    {{
                                        \Carbon\Carbon::parse($st->diterima_tanggal)
                                            ->diff(\Carbon\Carbon::parse($st->updated_at))
                                            ->format('%d hari, %h jam, %i menit, %s detik')
                                    }}
                                </p>
                            @elseif($st->status_disposisi == 'Ditolak')
                                <p class="text-danger">
                                    <strong>Ditolak Dalam:</strong>
                                    {{
                                        \Carbon\Carbon::parse($st->diterima_tanggal)
                                            ->diff(\Carbon\Carbon::parse($st->updated_at))
                                            ->format('%d hari, %h jam, %i menit, %s detik')
                                    }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- DETAIL FIELD SURAT TUGAS -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Jenis Kegiatan</strong></label>
                                <input type="text" class="form-control" value="{{ $st->jenisKegiatan->nama ?? '-' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Mulai</strong></label>
                                <input type="text" class="form-control" value="{{ $st->tanggal_mulai }}" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Selesai</strong></label>
                                <input type="text" class="form-control" value="{{ $st->tanggal_selesai }}" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Lokasi Kegiatan</strong></label>
                                <input type="text" class="form-control" value="{{ $st->lokasi_kegiatan }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Instansi Terkait</strong></label>
                                @php
                                    $instansiTerkait = $st->instansi->pluck('instansi.nama')->filter()->implode(', ');
                                    $instansiManual = $st->instansi->pluck('nama_manual')->filter()->implode(', ');
                                    $instansiGabung = trim($instansiTerkait . ($instansiTerkait && $instansiManual ? ', ' : '') . $instansiManual);
                                @endphp
                                <textarea class="form-control" rows="2" readonly>{{ $instansiGabung ?: '-' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label><strong>Soft File</strong></label><br>
                                @if ($st->soft_file)
                                    @php $files = json_decode($st->soft_file, true); @endphp
                                    <div class="d-flex flex-wrap">
                                        @foreach ($files as $file)
                                            <a href="{{ asset('storage/'.$file) }}" target="_blank" class="badge badge-primary mb-2 mr-2">
                                                📂 Lihat File
                                            </a>
                                        @endforeach
                                    </div>
                                @elseif ($st->soft_file_link)
                                    <a href="{{ $st->soft_file_link }}" target="_blank">{{ $st->soft_file_link }}</a>
                                @else
                                    <p class="text-muted">Tidak ada file.</p>
                                @endif
                            </div>

                            <div class="form-group">
                                <label><strong>Daftar Penugasan</strong></label>
                                @forelse ($st->penugasan as $p)
                                    <p class="mb-1">
                                        <strong>{{ $p->dosen->nama ?? $p->nama_manual }}</strong><br>
                                        <small>Peran: {{ $p->peranTugas->nama ?? '-' }}</small><br>
                                        <small>Unit: {{ $p->unit_asal }}</small>
                                    </p>
                                @empty
                                    <p class="text-muted">Belum ada penugasan.</p>
                                @endforelse
                            </div>
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
                                @foreach($st->modalDisposisi as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $m->tujuan }}</td>
                                    <td>
                                        <span class="badge badge-pill badge-{{ $m->status == 'Disetujui' ? 'success' : ($m->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                            {{ $m->status }}
                                        </span>
                                    </td>
                                    <td>{{ $m->tanggal_diterima ? \Carbon\Carbon::parse($m->tanggal_diterima)->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td>{{ $m->tanggal_proses ? \Carbon\Carbon::parse($m->tanggal_proses)->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td>{{ $m->diverifikasi_oleh ?? '-' }}</td>
                                    <td>{{ $m->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($st->status_disposisi == 'Selesai')
                        <a href="{{ route('pemohon.surat-tugas.pdf', $st->id) }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak PDF
                        </a>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Feedback -->
    <div class="modal fade" id="alasanModal{{ $st->id }}" tabindex="-1" role="dialog" aria-labelledby="alasanModalLabel{{ $st->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-center" role="document">
            <div class="modal-content shadow-sm">
                <div class="modal-header" style="background-color: #2C3E50; color: white;">
                    <h5 class="modal-title" id="alasanModalLabel{{ $st->id }}">
                        {{ $st->status_disposisi == 'Selesai' ? 'Pesan Tindak Lanjut' : 'Alasan Penolakan' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 15px;">
                    <p><strong>{{ $st->status_disposisi == 'Selesai' ? 'Pesan Tindak Lanjut:' : 'Alasan Penolakan:' }}</strong></p>
                    <p>{{ $st->alasan_penolakan }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- TEMPLATE PENUGASAN -->
<template id="penugasan-template">
  <div class="border p-3 mb-2 penugasan-item rounded" data-index="__INDEX__">
    <div class="row">
      <div class="col-md-4">
        <select name="penugasan[__INDEX__][dosen_id]" class="form-control dosen-select">
          <option value="">-- Pilih Dosen --</option>
          @foreach ($dosenList as $d)
            <option value="{{ $d->id }}" data-unit="{{ $d->unit->nama ?? '' }}">{{ $d->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <select name="penugasan[__INDEX__][peran_tugas_id]" class="form-control" required>
          <option value="">-- Pilih Peran --</option>
          @foreach ($peranList as $pr)
            <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="penugasan[__INDEX__][unit_asal]" class="form-control unit-asal" placeholder="Unit Asal" readonly required>
      </div>
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary add-dosen-manual mt-2">+ Dosen Manual</button>
    <button type="button" class="btn btn-sm btn-danger remove-penugasan mt-2">Hapus</button>
  </div>
</template>

<template id="penugasan-template-edit">
  <div class="border p-3 mb-2 penugasan-item" data-index="__INDEX__">
    <div class="row">
      <div class="col-md-4">
        <select name="penugasan[__INDEX__][dosen_id]" class="form-control dosen-select">
          <option value="">-- Pilih Dosen --</option>
          @foreach ($dosenList as $d)
            <option value="{{ $d->id }}" data-unit="{{ $d->unit->nama ?? '' }}">{{ $d->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <select name="penugasan[__INDEX__][peran_tugas_id]" class="form-control" required>
          <option value="">-- Pilih Peran --</option>
          @foreach ($peranList as $pr)
            <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="penugasan[__INDEX__][unit_asal]" class="form-control unit-asal" placeholder="Unit Asal" readonly required>
      </div>
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary add-dosen-manual mt-2">+ Dosen Manual</button>
    <button type="button" class="btn btn-sm btn-danger remove-penugasan mt-2">Hapus</button>
  </div>
</template>

@push('js')
<script>
$(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    // === CREATE ===
    $(document).on('click', '#add-instansi-manual', function() {
        $('#instansi-manual-wrapper').append(`
            <div class="input-group mt-2 instansi-manual-item">
            <input type="text" name="instansi_manual[]" class="form-control" placeholder="Nama Instansi Manual">
            <div class="input-group-append">
                <button class="btn btn-danger remove-instansi-manual" type="button"><i class="fas fa-times"></i></button>
            </div>
            </div>
        `);
        });

    $(document).on('click', '#add-penugasan', function() {
        let index = $('#penugasan-wrapper .penugasan-item').length; // hitung index

        let html = $('#penugasan-template').html().replace(/__INDEX__/g, index);
        $('#penugasan-wrapper').append(html);
    });

    // === EDIT ===
    $(document).on('click', '.add-instansi-manual', function() {
        let id = $(this).data('id');
        $('#instansi-manual-wrapper-' + id).append(`
            <div class="input-group mt-2 instansi-manual-item">
            <input type="text" name="instansi_manual[]" class="form-control" placeholder="Nama Instansi Manual">
            <div class="input-group-append">
                <button class="btn btn-danger remove-instansi-manual" type="button"><i class="fas fa-times"></i></button>
            </div>
            </div>
        `);
        });

    $(document).on('click', '.add-penugasan-edit', function() {
        const id = $(this).data('id');
        const wrapper = $('#penugasan-wrapper-' + id);
        let index = parseInt(wrapper.data('nextindex'));

        const html = $('#penugasan-template-edit').html().replace(/__INDEX__/g, index);
        wrapper.append(html);
        wrapper.data('nextindex', index + 1);
    });

    // === Universal ===
    $(document).on('change', '.dosen-select', function() {
        let unit = $(this).find(':selected').data('unit') ?? '';
        $(this).closest('.penugasan-item').find('.unit-asal').val(unit);
    });

    $(document).on('click', '.add-dosen-manual', function() {
        let wrap = $(this).closest('.penugasan-item');
        let index = wrap.attr('data-index');

        wrap.find('.dosen-select').closest('.col-md-4').remove();

        let manualInput = `
            <div class="col-md-4">
            <input type="text" name="penugasan[${index}][nama_manual]" class="form-control" placeholder="Nama Dosen Manual" required>
            </div>
        `;

        wrap.find('.row').prepend(manualInput);

        wrap.find('.unit-asal')
            .prop('readonly', false)
            .attr('placeholder', 'Unit Asal Manual')
            .attr('required', true);

        $(this).remove();
    });

    $(document).on('click', '.remove-penugasan', function() {
        $(this).closest('.penugasan-item').remove();
    });

    $(document).on('click', '.remove-instansi-manual', function() {
        $(this).closest('.instansi-manual-item').remove();
    });
    setTimeout(function() {
        $('#success-alert').fadeOut('slow');
    }, 3000);

    setTimeout(function() {
        $('.alert-danger').fadeOut('slow');
    }, 3000);
});
</script>

<style>
/* Warna badge tag instansi: biru/tosca */
.select2-selection__choice {
  background-color: #3498DB !important; /* Biru solid Bootstrap */
  border: none;
  color: #fff;
}

/* Warna '×' remove di tag */
.select2-selection__choice__remove {
  color: #fff !important; /* Biar kontras, putih */
  margin-right: 5px;
}

/* Hover remove badge */
.select2-selection__choice__remove:hover {
  color: #FF4D4D !important; /* Merah solid pas hover */
}
</style>
@endpush

