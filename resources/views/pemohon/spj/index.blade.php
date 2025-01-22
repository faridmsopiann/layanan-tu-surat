@extends('pemohon.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@push('css')
<style>
    .rating {
        display: inline-flex;
        font-size: 3rem;
        cursor: pointer;
    }

    .star {
        color: #ddd;
        transition: color 0.3s;
    }

    .star.hovered,
    .star.selected {
        color: #f5b301;
    }
</style>
@endpush

@section('content')
<div class="container pt-4 pb-4 mb-5">
    <h2 class="mb-4" style="font-weight: 700; color: #2C3E50;">Pengajuan SPJ</h2>


    @if(session('success'))
    <div id="success-alert" class="alert alert-success mb-3 shadow-sm" style="border-left: 5px solid #28a745;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tabel Proposal -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-info">
                <tr>
                    <th class="text-sm">No</th>
                    <th class="text-sm">Pengajuan Surat</th>
                    <th class="text-sm">Tanggal Pengajuan</th>
                    <th class="text-sm">Tanggal Selesai</th>
                    <th class="text-sm">Status</th>
                    <th class="text-sm">Catatan</th>
                    <th class="text-sm" width="11%">Rating</th>
                    <th class="text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spjs as $spj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $spj->jenis }} <br>
                        <small>
                            <a href="{{ asset('storage/' . $spj->proposal->soft_file) }}" target="_blank" style="color: #2980B9; text-decoration: none;">
                                <i class="fas fa-file-pdf"></i> Lihat File
                            </a>
                        </small>
                    </td>
                    <td>{{ $spj->tanggal_proses ? \Carbon\Carbon::parse($spj->tanggal_proses)->format('d F Y H:i'): '-' }}</td>
                    <td>{{ $spj->tanggal_selesai ? \Carbon\Carbon::parse($spj->tanggal_selesai)->format('d F Y H:i') : '-' }}</td>
                    <td>
                        @if($spj->status == 'Pending')
                        <span class="badge badge-warning">Diproses</span>
                        @elseif($spj->status == 'Revisi')
                        <span class="badge badge-danger">Revisi</span>
                        @elseif($spj->status == 'Selesai')
                        <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($spj->status == 'Selesai')
                        Mohon kirimkan hard file ke divisi Keuangan
                        @else
                        {{ $spj->catatan ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if($spj->rating)
                        @for($i = 1; $i <= 5; $i++)
                            <span data-value="{{ $i }}" class="star" style="{{ $i <= $spj->rating->rating ? 'color: #f5b301' : '' }}">&#9733;</span>
                            @endfor
                            @else
                            -
                            @endif
                    </td>
                    <td>
                        @if($spj->status == 'Selesai' && !$spj->rating)
                        <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-id="{{ $spj->id }}" data-target="#ratingModal"><i class="fas fa-star"></i></button>
                        @endif
                        <a href="{{ route('pemohon.spj.show', $spj->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-share"></i>
                        </a>
                    </td>

                    <div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content shadow-sm">
                                <form action="{{ route('pemohon.spj.rating') }}" method="POST">
                                    @csrf
                                    @method('POST')

                                    <input type="hidden" name="spj_id" id="spj_id">

                                    <div class="modal-body text-center">
                                        <div id="starRating" class="rating" data-selected="0">
                                            <span data-value="1" class="star">&#9733;</span>
                                            <span data-value="2" class="star">&#9733;</span>
                                            <span data-value="3" class="star">&#9733;</span>
                                            <span data-value="4" class="star">&#9733;</span>
                                            <span data-value="5" class="star">&#9733;</span>
                                        </div>
                                        <input type="hidden" name="rating" id="ratingInput" value="0">
                                    </div>

                                    <div class="modal-body" style="font-size: 15px;">
                                        <div class="form-group">
                                            <textarea name="catatan" id="catatan" class="form-control" placeholder="Masukan pesan..." rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </tr>
                @endforeach
            </tbody>
        </table>
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

        $("#starRating .star").each(function() {
            $(this).on("mouseover", function() {
                const value = $(this).attr("data-value");
                $("#starRating .star").each(function() {
                    $(this).toggleClass("hovered", $(this).attr("data-value") <= value);
                });
            });

            $(this).on("mouseout", function() {
                $("#starRating .star").each(function() {
                    $(this).removeClass("hovered");
                });
            });

            $(this).on("click", function() {
                const value = $(this).attr("data-value");
                $("#ratingInput").val(value);
                $("#starRating .star").each(function() {
                    $(this).toggleClass("selected", $(this).attr("data-value") <= value);
                });
            });
        });
    })
</script>
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#ratingModal').on('shown.bs.modal', function(e) {
            $('#name').focus();
            let button = $(e.relatedTarget)
            let modal = $(this)
            modal.find('#spj_id').val(button.data('id'));
        })
    });

    document.addEventListener("DOMContentLoaded", function() {
        var successAlert = document.getElementById("success-alert");

        if (successAlert) {
            setTimeout(function() {
                successAlert.classList.add("fade-out");
                setTimeout(function() {
                    successAlert.remove();
                }, 500); // Hapus elemen setelah animasi selesai
            }, 1000);
        }
    });
</script>

<!-- Tambahkan CSS untuk transisi -->
<style>
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
</style>
@endpush