@extends('pemohon.layouts.app')

@section('content')
<div class="container pb-4">
    <h1 class="mt-4 mb-4" style="font-weight: 700; color: #2C3E50;">Pengajuan Proposal Baru</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pemohon.proposals.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="tanggal_surat">Tanggal Surat</label>
            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
        </div>

        <div class="form-group">
            <label for="jenis_proposal">Jenis Proposal</label>
            <select class="form-control" id="jenis_proposal" name="jenis_proposal" required>
                <option value="">Pilih Jenis Proposal</option>
                <option value="Disertai Pengajuan Dana">Disertai Pengajuan Dana</option>
                <option value="Tanpa Pengajuan Dana">Tanpa Pengajuan Dana</option>
            </select>
        </div>        

        <div class="form-group">
            <label for="asal_surat">Asal Surat</label>
            <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
        </div>

        <div class="form-group">
            <label for="hal">Perihal</label>
            <input type="text" class="form-control" id="hal" name="hal" required>
        </div>

        <div class="form-group">
            <label for="soft_file">Upload Soft File (PDF):</label>
            <input type="file" class="form-control" name="soft_file" accept="application/pdf">
        </div>

        <button type="submit" class="btn btn-primary">Ajukan Proposal</button>
    </form>
</div>
@endsection
