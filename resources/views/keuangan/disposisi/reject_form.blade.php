@extends('keuangan.layouts.app')

@section('title', 'Form Reject Proposal')

@section('content')
<div class="container pb-4">
    <h3 class="mt-4">Form Reject Proposal</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('keuangan.disposisi.updateReject', $proposal->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="dari">Dari</label>
                    <select class="form-control" id="dari" name="dari">
                        <option value="Keuangan">Keuangan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="disposisi">Tujuan Reject</label>
                    <select id="disposisi" class="form-control" name="disposisi" disabled>
                        <option value="Staff TU">Staff TU</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pesan_disposisi">Pesan Reject</label>
                    <textarea id="pesan_disposisi" class="form-control" name="pesan_disposisi" rows="3" required>{{ old('pesan_disposisi') }}</textarea>
                </div>

                <button type="submit" class="btn btn-danger">Reject</button>
                <a href="{{ route('keuangan.disposisi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
