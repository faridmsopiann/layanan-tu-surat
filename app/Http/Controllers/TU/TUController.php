<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class TUController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id(); // Mendapatkan ID pemohon yang sedang login

        // Menghitung jumlah proposal yang diajukan oleh pemohon
        $totalProposals = Proposal::withTrashed()->count();

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::whereNotIn('status_disposisi', ['Selesai', 'Ditolak'])
            ->count();

        // Menghitung proposal yang status_disposisinya 'Selesai'
        $approvedProposals = Proposal::withTrashed()
            ->where('status_disposisi', 'Selesai')
            ->count();

        // Menghitung proposal yang status_disposisinya 'Ditolak'
        $rejectedProposals = Proposal::where('status_disposisi', 'Ditolak')
            ->count();

        // Mengirim data ke view
        return view('tu.dashboard', compact(
            'totalProposals',
            'pendingApprovals',
            'approvedProposals',
            'rejectedProposals'
        ));
    }
}
