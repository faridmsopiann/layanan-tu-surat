<?php

namespace App\Http\Controllers\ProdiFisika;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiFisikaController extends Controller
{
    public function dashboard()
    {

        $totalProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Fisika');
        })->withTrashed()->count();

        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Fisika')
            ->count();

        $approvedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Fisika');
        })
            ->where('status_disposisi', 'Selesai')
            ->withTrashed()
            ->count();

        $rejectedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Fisika');
        })->where('status_disposisi', 'Ditolak')
            ->withTrashed()
            ->count();

        // Mengirim data ke view
        return view('prodi-fisika.dashboard', compact(
            'pendingApprovals',
            'totalProposals',
            'approvedProposals',
            'rejectedProposals',
        ));
    }
}
