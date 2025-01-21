@extends('pemohon.layouts.app')

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
            <form method="POST" action="{{ route('pemohon.spj.store') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposalId }}" class="form-control" required>

                <div class="form-group">
                    <label for="dari">Kategori SPJ</label>
                    <input type="text" name="jenis" id="jenis" class="form-control" placeholder="Masukan kategori SPJ..." required>
                </div>

                <label for="dari">Dokumen SPJ</label>
                <table class="table table-borderless" id="spj-documents">
                    <thead>
                        <tr>
                            <th style=" width: 1%; padding-bottom: 17px;" class="text-center">No</th>
                            <th>Jenis Dokumen <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i>&nbsp;Tambah Jenis Dokumen</button></th>
                            <th style="width: 35%; padding-bottom: 17px;">File</th>
                            <th class="text-center" style="width: 1%; padding-bottom: 17px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" id="iteration">1</td>
                            <td>
                                <select name="categories[]" id="categories" class="form-control" required>
                                    <option value="" selected disabled>--Pilih Jenis Dokumen--</option>
                                    @foreach(App\Models\SpjDocumentCategory::pluck('nama', 'id') as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="file" name="files[]" id="files" class="form-control" accept="application/pdf" required>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button style="margin-left: 57px;" class="btn btn-sm btn-outline-primary" id="add-document" type="button"><i class="fas fa-plus"></i>&nbsp;Tambah</button>

                <hr>

                <div class="float-right">
                    <a href="{{ route('pemohon.proposals.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-sm">
            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                <h5 class="modal-title" id="createModalLabel">Tambah Jenis Dokumen</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pemohon.spj.document.category.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="modal-body" style="font-size: 15px;">
                    <div class="form-group">
                        <label for="nama">Jenis Dokumen</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
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

        $(document).on('click', '#add-document', function(e) {
            let row = `
                <tr>
                    <td class="text-center" id="iteration">1</td>
                    <td>
                        <select name="categories[]" id="categories" class="form-control" required>
                            <option value="" selected disabled>--Pilih Jenis Dokumen--</option>
                            @foreach(App\Models\SpjDocumentCategory::pluck('nama', 'id') as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="file" name="files[]" id="files" accept="application/pdf" class="form-control" required>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger" id="remove-document" type="button"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `
            $('#spj-documents').find('tbody').append(row)
            iteration()
        })

        $(document).on('click', '#remove-document', function(e) {
            const row = $(this).closest('tr').remove()
            iteration()
        })

        function iteration() {
            $('#spj-documents').find('tbody tr').each(function(index) {
                $(this).find('#iteration').text(index + 1);
            });
        }
    })
</script>
@endpush