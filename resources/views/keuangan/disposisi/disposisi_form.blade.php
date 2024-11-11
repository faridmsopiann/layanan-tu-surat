@extends('keuangan.layouts.app')

@section('title', 'Form Disposisi Proposal')

@section('content')
<div class="container pb-4">
    <h3 class="mt-4">Form Disposisi Proposal</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('keuangan.disposisi.updateDisposisi', $proposal->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="dari">Dari</label>
                    <select class="form-control" id="dari" name="dari">
                        <option value="Keuangan">Keuangan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="disposisi">Tujuan Disposisi</label>
                    <select id="disposisi" class="form-control" name="disposisi" disabled>
                        <option value="Staff TU">Staff Tata Usaha</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pesan_disposisi">Pesan Disposisi</label>
                    <textarea id="pesan_disposisi" class="form-control" name="pesan_disposisi" rows="3" required>{{ old('pesan_disposisi') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Disposisi</button>
                <a href="{{ route('keuangan.disposisi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
