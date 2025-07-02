<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Surat Tugas</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            color: #333; 
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }

        .kop h1 {
            margin: 0;
            font-size: 18px;
        }

        .kop p {
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #666;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
        }

        .status.Diproses { background: #f1c40f; }
        .status.Selesai, .status.Disetujui { background: #2ecc71; }
        .status.Ditolak { background: #e74c3c; }

        h2 {
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop">
        <h1>INSTANSI ANDA</h1>
        <p>Jl. Contoh Alamat No. 123, Jakarta | Telp: (021) 123456 | www.contohinstansi.go.id</p>
    </div>

    <h2>Detail Surat Tugas</h2>
    <table>
        <tr><td><strong>Kode Pengajuan:</strong></td><td>{{ $proposal->kode_pengajuan }}</td></tr>
        <tr><td><strong>Nomor Agenda:</strong></td><td>{{ $proposal->nomor_agenda }}</td></tr>
        <tr><td><strong>Tanggal Surat:</strong></td><td>{{ $proposal->tanggal_surat }}</td></tr>
        <tr><td><strong>Asal Surat:</strong></td><td>{{ $proposal->asal_surat }}</td></tr>
        <tr><td><strong>Hal:</strong></td><td>{{ $proposal->hal }}</td></tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td>
                <span class="status {{ $proposal->status_disposisi }}">{{ $proposal->status_disposisi }}</span>
            </td>
        </tr>
        <tr><td><strong>Nama Pemohon:</strong></td><td>{{ $proposal->pemohon->name }}</td></tr>
        <tr><td><strong>Diterima Tanggal:</strong></td><td>{{ $proposal->diterima_tanggal }}</td></tr>
    </table>

    <h2>Detail Kegiatan</h2>
    <table>
        <tr><td><strong>Jenis Kegiatan:</strong></td><td>{{ $proposal->jenisKegiatan->nama ?? '-' }}</td></tr>
        <tr><td><strong>Tanggal Mulai:</strong></td><td>{{ $proposal->tanggal_mulai }}</td></tr>
        <tr><td><strong>Tanggal Selesai:</strong></td><td>{{ $proposal->tanggal_selesai }}</td></tr>
        <tr><td><strong>Lokasi Kegiatan:</strong></td><td>{{ $proposal->lokasi_kegiatan }}</td></tr>
        <tr>
            <td><strong>Instansi Terkait:</strong></td>
            <td>
                @php
                    $instansiTerkait = $proposal->instansi->pluck('instansi.nama')->filter()->implode(', ');
                    $instansiManual = $proposal->instansi->pluck('nama_manual')->filter()->implode(', ');
                    $instansiGabung = trim($instansiTerkait . ($instansiTerkait && $instansiManual ? ', ' : '') . $instansiManual);
                @endphp
                {{ $instansiGabung ?: '-' }}
            </td>
        </tr>
        <tr>
            <td><strong>Daftar Penugasan:</strong></td>
            <td>
                @forelse ($proposal->penugasan as $p)
                    {{ $p->dosen->nama ?? $p->nama_manual }} ({{ $p->peranTugas->nama ?? '-' }}) - Unit: {{ $p->unit_asal }}<br>
                @empty
                    Belum ada penugasan.
                @endforelse
            </td>
        </tr>
    </table>

    <h2>Detail Disposisi</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Tgl Diterima</th>
                <th>Tgl Proses</th>
                <th>Diverifikasi Oleh</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proposal->modalDisposisi as $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $m->tujuan }}</td>
                <td><span class="status {{ $m->status }}">{{ $m->status }}</span></td>
                <td>{{ $m->tanggal_diterima ?? '-' }}</td>
                <td>{{ $m->tanggal_proses ?? '-' }}</td>
                <td>{{ $m->diverifikasi_oleh ?? '-' }}</td>
                <td>{{ $m->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
