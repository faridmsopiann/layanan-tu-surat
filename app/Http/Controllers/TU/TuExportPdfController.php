<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;

class TuExportPdfController extends Controller
{
    public function exportPdfSuratMasuk($id)
    {
        $proposal = Proposal::with(['pemohon', 'modalDisposisi'])
            ->findOrFail($id);

        if ($proposal->status_disposisi !== 'Selesai') {
            abort(403, 'Surat belum selesai.');
        }

        // Surat Masuk ➜ HANYA data utama + modalDisposisi
        $pdf = Pdf::loadView('pdf.proposal_detail', compact('proposal'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('surat-masuk-'.$proposal->kode_pengajuan.'.pdf');
    }

    public function exportPdfSuratTugas($id)
    {
        $proposal = Proposal::with(['pemohon', 'jenisKegiatan', 'instansi', 'penugasan', 'penugasan.peranTugas', 'modalDisposisi'])
            ->findOrFail($id);

        if ($proposal->status_disposisi !== 'Selesai') {
            abort(403, 'Surat belum selesai.');
        }

        // Surat Tugas ➜ Semua detail KECUALI file
        $pdf = Pdf::loadView('pdf.surat_tugas_detail', compact('proposal'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('surat-tugas-'.$proposal->kode_pengajuan.'.pdf');
    }
}