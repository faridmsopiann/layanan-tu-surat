@extends('tu.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pb-4">
    <h1 class="mb-4 mt-4">Buat Proposal Baru</h1>

    <form action="{{ route('tu.proposals.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nomor_agenda" class="form-label">Nomor Agenda</label>
            <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
        </div>

        <div class="mb-3">
            <label for="nomor_surat" class="form-label">Nomor Surat</label>
            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
        </div>

        <div class="mb-3">
            <label for="asal_surat" class="form-label">Asal Surat</label>
            <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
        </div>

        <div class="mb-3">
            <label for="hal" class="form-label">Hal</label>
            <input type="text" class="form-control" id="hal" name="hal" required>
        </div>

        <div class="mb-3">
            <label for="diterima_tanggal" class="form-label">Diterima Tanggal</label>
            <input type="date" class="form-control" id="diterima_tanggal" name="diterima_tanggal" required>
        </div>

        <div class="mb-3">
            <label for="untuk" class="form-label">Untuk</label>
            <input type="text" class="form-control" id="untuk" name="untuk" required>
        </div>

        <div class="mb-3">
            <label for="status_disposisi" class="form-label">Status Disposisi</label>
            <select class="form-control" id="status_disposisi" name="status_disposisi" required>
                <option value="Memproses">Memproses</option>
                <option value="Menunggu Approval Dekan">Menunggu Approval Dekan</option>
                <option value="Menunggu Approval Kabag">Menunggu Approval Kabag</option>
                <option value="Menunggu Approval Keuangan">Menunggu Approval Keuangan</option>
                <option value="Selesai">Selesai</option>
                <option value="Ditolak">Ditolak</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mb-5">Simpan</button>
    </form>
</div>
@endsection
