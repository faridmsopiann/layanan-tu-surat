<?php

namespace App\Http\Controllers\ProdiTP;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiTPController extends Controller
{
    public function dashboard()
    {

        $totalProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Teknik Pertambangan');
        })->withTrashed()->count();

        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Teknik Pertambangan')
            ->count();

        $approvedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Teknik Pertambangan');
        })
            ->where('status_disposisi', 'Selesai')
            ->withTrashed()
            ->count();

        $rejectedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Teknik Pertambangan');
        })->where('status_disposisi', 'Ditolak')
            ->withTrashed()
            ->count();

        // Mengirim data ke view
        return view('prodi-tp.dashboard', compact(
            'pendingApprovals',
            'totalProposals',
            'approvedProposals',
            'rejectedProposals',
        ));
    }
}
