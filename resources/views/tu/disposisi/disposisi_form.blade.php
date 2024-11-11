@extends('tu.layouts.app')

@section('content')
<div class="container pb-4">
    <h3 class="mt-4 mb-4" style="font-weight: 700; color: #2C3E50;">Form Disposisi Proposal</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tu.disposisi.update', $proposal->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="dari">Dari</label>
                    <select class="form-control" id="dari" name="dari">
                        @if ($proposal->status_disposisi == 'Memproses')
                            <option value="Staff Tata Usaha">Staff Tata Usaha</option>
                        @elseif ($proposal->status_disposisi == 'Menunggu Approval Kabag')
                            <option value="Kabag Tata Usaha">Kabag Tata Usaha</option>
                        @endif  
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="disposisi">Pilih Tujuan Disposisi</label>
                    <select id="disposisi" class="form-control" name="disposisi" required>
                        @if($proposal->status_disposisi == 'Memproses')
                            <option value="Dekan">Dekan</option>
                            <option value="Wadek Akademik">Wadek Akademik</option>
                            <option value="Wadek Kemahasiswaan">Wadek Kemahasiswaan</option>
                            <option value="Wadek Administrasi Umum">Wadek Administrasi Umum</option>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag' && $proposal->jenis_proposal == 'Disertai Pengajuan Dana')
                            <option value="Keuangan">Keuangan</option>
                        @elseif($proposal->status_disposisi == 'Menunggu Approval Kabag' && $proposal->jenis_proposal == 'Tanpa Pengajuan Dana')
                            <option value="Staff TU">Staff TU</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="pesan_disposisi">Pesan Disposisi</label>
                    <textarea id="pesan_disposisi" class="form-control" name="pesan_disposisi" rows="3" required>{{ old('pesan_disposisi') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Disposisi</button>
                <a href="{{ route('tu.disposisi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
