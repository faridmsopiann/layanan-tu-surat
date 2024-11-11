<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalMasukController extends Controller
{
    // Tampilkan semua proposal masuk
    public function index()
    {
        $proposals = Proposal::all(); // Mengambil semua proposal masuk
        return view('tu.proposals.index', compact('proposals')); // Return view TU dengan data proposal
    }

    // Update status pengajuan proposal
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_disposisi' => 'required|in:Memproses,Menunggu Approval Dekan,Menunggu Approval Kabag,Menunggu Approval Keuangan,Selesai,Ditolak',
        ]);

        $proposal = PengajuanSurat::findOrFail($id); // Cari proposal berdasarkan ID
        $proposal->update([
            'status_disposisi' => $request->status, // Update status sesuai input dari form
        ]);

        return redirect()->route('tu.proposals.index')->with('success', 'Status proposal berhasil diperbarui.');
    }
}
