@extends('keuangan.layouts.app')

@section('content')

<div class="container pb-4">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h2 class="pt-4 pb-4">Lampiran Surat Pertanggungjawaban</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('keuangan.spj.edit', $spj->id) }}" id="updateFrom">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="dari">Kategori SPJ</label>
                    <input type="text" class="form-control" value="{{ $spj->jenis }}" readonly>
                </div>

                <label for="dari">Dokumen SPJ</label>
                <table class="table table-borderless" id="spj-documents">
                    <thead>
                        <tr>
                            <th style=" width: 1%;" class="text-center">No</th>
                            <th>Jenis Dokumen</th>
                            <th style="width: 35%;">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($spj->documents as $document)
                        <tr>
                            <td class="text-center" id="iteration">{{ $loop->iteration }}</td>
                            <td>{{ $document->category->nama }}
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $document->file_url) }}" target="_blank" style="color: #2980B9; text-decoration: none;">
                                    <i class="fas fa-file-pdf"></i> Lihat File
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="form-control" placeholder="Masukan catatan..." name="catatan" rows="3" cols="3" id="catatan" required @readonly(!$spj->status == 'Selesai')>{{ $spj->catatan }}</textarea>
                </div>

                <input type="hidden" name="type" id="type">

                <div class="float-right">
                    <a href="{{ route('keuangan.spj.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    <button type="button" class="btn btn-sm btn-warning" id="btn-revisi">Revisi</button>
                    @if($spj->status != 'Selesai')
                    <button type="button" class="btn btn-sm btn-success" id="btn-setuju">Setujui</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        $(document).on('click', '#btn-revisi, #btn-setuju', function(e) {
            e.preventDefault();
            let textarea = $('#catatan');
            let form = $('#updateFrom');
        });

        $(document).on('click', '#btn-revisi, #btn-setuju', function(e) {
            e.preventDefault();

            let textarea = $('#catatan');
            let type = $('#type');
            let form = $('#updateFrom');

            if ($(this).attr('id') === 'btn-revisi') {
                type.val('revisi')
                if (!textarea.val().trim()) {
                    alert('Mohon masukan catatan.');
                    textarea.focus();
                    return;
                }
            } else {
                type.val('setuju')
            }

            $('#updateFrom').submit()
        });
    })
</script>
@endpush