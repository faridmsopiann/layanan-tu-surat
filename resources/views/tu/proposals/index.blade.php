@extends('tu.layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">Proposal Masuk</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemohon</th>
                <th>Tanggal Surat</th>
                <th>Asal Surat</th>
                <th>Hal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proposals as $proposal)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $proposal->pemohon->name }}</td> <!-- Pemohon adalah relasi dengan user -->
                <td>{{ $proposal->tanggal_surat }}</td>
                <td>{{ $proposal->asal_surat }}</td>
                <td>{{ $proposal->hal }}</td>
                <td>
                    <span class="badge badge-info">{{ $proposal->status_disposisi }}</span>
                </td>
                <td>
                    <!-- Form untuk update status -->
                    <form action="{{ route('tu.proposals.update-status', $proposal->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control mb-2">
                            <option value="Memproses" {{ $proposal->status == 'Memproses' ? 'selected' : '' }}>Memproses</option>
                            <option value="Menunggu Approval Dekan" {{ $proposal->status == 'Menunggu Approval Dekan' ? 'selected' : '' }}>Menunggu Approval Dekan</option>
                            <option value="Menunggu Approval Kabag" {{ $proposal->status == 'Menunggu Approval Kabag' ? 'selected' : '' }}>Menunggu Approval Kabag</option>
                            <option value="Menunggu Approval Keuangan" {{ $proposal->status == 'Menunggu Approval Keuangan' ? 'selected' : '' }}>Menunggu Approval Keuangan</option>
                            <option value="Selesai" {{ $proposal->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ $proposal->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
