<?php

namespace App\Http\Controllers\ProdiMatematika;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiMatematikaController extends Controller
{
    public function dashboard()
    {

        $totalProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Matematika');
        })->withTrashed()->count();

        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Matematika')
            ->count();

        $approvedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Matematika');
        })
            ->where('status_disposisi', 'Selesai')
            ->withTrashed()
            ->count();

        $rejectedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Matematika');
        })->where('status_disposisi', 'Ditolak')
            ->withTrashed()
            ->count();

        // Mengirim data ke view
        return view('prodi-matematika.dashboard', compact(
            'pendingApprovals',
            'totalProposals',
            'approvedProposals',
            'rejectedProposals',
        ));
    }
}
