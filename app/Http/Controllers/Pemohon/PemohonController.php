<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PemohonController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id(); // Mendapatkan ID pemohon yang sedang login

        // Menghitung jumlah proposal yang diajukan oleh pemohon
        $totalProposals = Proposal::where('pemohon_id', $userId)->count();

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('pemohon_id', $userId)
            ->whereNotIn('status_disposisi', ['Selesai', 'Ditolak'])
            ->count();

        // Menghitung proposal yang status_disposisinya 'Selesai'
        $approvedProposals = Proposal::where('pemohon_id', $userId)
            ->where('status_disposisi', 'Selesai')
            ->count();

        // Menghitung proposal yang status_disposisinya 'Ditolak'
        $rejectedProposals = Proposal::where('pemohon_id', $userId)
            ->where('status_disposisi', 'Ditolak')
            ->count();

        // Mengirim data ke view
        return view('pemohon.dashboard', compact(
            'totalProposals',
            'pendingApprovals',
            'approvedProposals',
            'rejectedProposals'
        ));
    }
}
