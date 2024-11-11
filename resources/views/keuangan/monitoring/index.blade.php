@extends('keuangan.layouts.app')

@section('title', 'Monitoring Proposal')

@section('content')
<div class="container pt-4 pb-4 ml-0 mb-5">
    <h1 class="mb-4">Monitoring Proposal</h1>

    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Proposal -->
    <table class="table table-bordered">
        <thead>
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
            </tr>
        </thead>
        <tbody>
            @foreach($proposals as $proposal)
            <tr>
                <td class="text-sm">{{ $loop->iteration }}</td>
                <td class="text-sm">{{ $proposal->nomor_agenda }}</td>
                <td class="text-sm">
                    @if ($proposal->soft_file)
                        <a href="{{ asset('storage/' . $proposal->soft_file) }}" target="_blank">Lihat PDF</a>
                    @else
                        <span>Tidak ada</span>
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
                {{-- <td class="d-flex">
                    <!-- Tombol Disposisi -->
                    <a href="{{ route('disposisi.edit', $proposal->id) }}" class="btn btn-info btn-sm d-inline-block mr-1 w-100">
                        <i class="fas fa-share"></i> 
                    </a>
                    <!-- Tombol Reject -->
                    <a href="{{ route('disposisi.reject', $proposal->id) }}" class="btn btn-danger btn-sm d-inline-block w-100">
                        <i class="fas fa-times"></i> 
                    </a>
                </td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $proposals->links('pagination::bootstrap-5') !!}
</div>
@endsection
