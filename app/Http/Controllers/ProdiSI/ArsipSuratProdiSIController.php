<?php

namespace App\Http\Controllers\ProdiSI;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ArsipSuratProdiSIController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk menampilkan proposal yang berstatus 'Selesai' dan soft-deleted
        $query = Proposal::with(['pemohon', 'modalDisposisi'])
            ->whereIn('status_disposisi', ['Selesai', 'Ditolak'])
            ->whereHas('modalDisposisi', function ($q) {
                $q->where('tujuan', 'Prodi Sistem Informasi');
            })->withTrashed();

        // Filter berdasarkan input search
        if ($request->filled('kode_pengajuan')) {
            $query->where('kode_pengajuan', 'like', '%' . $request->kode_pengajuan . '%');
        }
        if ($request->filled('tanggal_surat')) {
            $query->whereDate('tanggal_surat', $request->tanggal_surat);
        }
        if ($request->filled('asal_surat')) {
            $query->where('asal_surat', 'like', '%' . $request->asal_surat . '%');
        }
        if ($request->filled('perihal')) {
            $query->where('hal', 'like', '%' . $request->perihal . '%');
        }

        // Pagination hasil filter
        $arsipProposals = $query->paginate(4);

        return view('prodi-si.arsip.index', compact('arsipProposals'));
    }


    // Hapus proposal
    public function destroy($id)
    {
        $proposal = Proposal::withTrashed()->find($id);
        $proposal->forceDelete();
        return redirect()->route('prodi-sistem-informasi.arsip.index')->with('success', 'Proposal berhasil dihapus.');
    }
}
