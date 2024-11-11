@extends('tu.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content')
<div class="container pt-4 pb-4">
    <h1 class="mb-4">Form Reject Proposal</h1>

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
@endsection
