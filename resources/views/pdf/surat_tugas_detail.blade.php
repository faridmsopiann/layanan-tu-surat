<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px; 
            line-height: 1.6;
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

        .content {
            margin-top: 20px;
        }

        .tengah {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14px;
            margin-top: 20px;
        }

        .nomor {
            text-align: center;
            margin-bottom: 20px;
        }

        table.detail {
            width: 100%;
            margin-top: 10px;
        }

        table.detail td {
            vertical-align: top;
        }

        .ttd {
            margin-top: 200px;
            text-align: right;
        }

        .ttd img {
            height: 80px;
        }

        .lampiran-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .lampiran-table th, .lampiran-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }

        .footer-fixed {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
        }
        .footer-fixed img {
            width: 80%;
            height: auto;
        }
    </style>
</head>
<body>
    {{-- kop --}}
    @if($kop && $kop->kop_surat)
    <div class="kop">
        <img src="{{ public_path($kop->kop_surat) }}" style="width: 100%; height: auto; margin-bottom: 20px;">
    </div>
    @endif

    {{-- JUDUL --}}
    <div class="tengah">SURAT TUGAS</div>
    <div class="nomor">Nomor: {{ $proposal->nomor_surat }}</div>

    {{-- ISI UTAMA --}}
    <div class="content">
        <table class="detail">
            <tr>
                <td width="18%">Menimbang</td>
                <td width="2%">:</td>
                <td>{{ $proposal->pertimbangan }}</td>
            </tr>
            <tr>
                <td>Dasar</td>
                <td>:</td>
                <td>{{ $proposal->dasar_penugasan }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Memberi Tugas</p>

        <table class="detail">
            <tr>
                <td width="18%">Kepada</td>
                <td width="2%">:</td>
                <td>Nama-nama terlampir</td>
            </tr>
            <tr>
                <td>Untuk</td>
                <td>:</td>
                <td>{{ $proposal->hal }}</td>
            </tr>
            <tr>
                <td>Sumber Biaya</td>
                <td>:</td>
                <td>{{ $proposal->sumber_biaya }}</td>
            </tr>
        </table>

        <div class="ttd">
            Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Dekan,<br><br>
            <img src="{{ public_path('images/ttd.png') }}"><br>
            <strong><u>Husni Teja Sukmana, S.T., M.Sc., Ph.D</u></strong><br>
            NIP. 197106082005011005
        </div>

        {{-- FOOTER GAMBAR --}}
        <div class="footer-fixed">
            <img src="{{ public_path('images/footer.png') }}">
        </div>
    </div>

    {{-- HALAMAN LAMPIRAN --}}
    <div style="page-break-before: always;"></div>

    <p><strong>Lampiran Surat Nomor:</strong> {{ $proposal->nomor_surat }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    @php
        $instansiTerkait = $proposal->instansi->pluck('instansi.nama')->filter()->implode(', ');
        $instansiManual = $proposal->instansi->pluck('nama_manual')->filter()->implode(', ');
        $instansiGabung = trim($instansiTerkait . ($instansiTerkait && $instansiManual ? ', ' : '') . $instansiManual);
    @endphp

    <p>
        DAFTAR NAMA DOSEN PADA {{ strtoupper($proposal->hal) }}
        PADA TANGGAL {{ \Carbon\Carbon::parse($proposal->tanggal_mulai)->translatedFormat('d') }}
        s/d {{ \Carbon\Carbon::parse($proposal->tanggal_selesai)->translatedFormat('d F Y') }}
        DI {{ $instansiGabung }}.
    </p>

    <table class="lampiran-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proposal->penugasan as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->pegawaiPenugasan->nama ?? $p->nama_manual }}</td>
                <td>{{ $p->jabatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd">
        Dekan,<br><br>
        <img src="{{ public_path('images/ttd.png') }}"><br>
        <strong><u>Husni Teja Sukmana, S.T., M.Sc., Ph.D</u></strong><br>
        NIP. 197710302001121003
    </div>
</body>
</html>
