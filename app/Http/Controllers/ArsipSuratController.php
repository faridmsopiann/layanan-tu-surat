<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class ArsipSuratController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk menampilkan proposal yang berstatus 'Selesai' dan soft-deleted
        $query = Proposal::with(['pemohon', 'modalDisposisi'])
            ->whereIn('status_disposisi', ['Selesai', 'Ditolak'])
            ->withTrashed();

        // Filter berdasarkan input search
        if ($request->filled('no_agenda')) {
            $query->where('nomor_agenda', 'like', '%' . $request->no_agenda . '%');
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
        $arsipProposals = $query->paginate(5);

        // Menampilkan proposal yang berstatus 'Selesai'
        // $arsipProposals = Proposal::with(['pemohon', 'modalDisposisi'])->withTrashed()->where('status_disposisi', 'Selesai')->paginate(5);

        return view('tu.arsip.index', compact('arsipProposals'));
    }


    // Hapus proposal
    public function destroy($id)
    {
        $proposal = Proposal::withTrashed()->find($id);
        $proposal->forceDelete();
        return redirect()->route('tu.arsip.index')->with('success', 'Proposal berhasil dihapus.');
    }
}
