<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        $totalProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Keuangan');
        })->withTrashed()->count();

        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Keuangan')
            ->count();

        $approvedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Keuangan');
        })
            ->where('status_disposisi', 'Selesai')
            ->withTrashed()
            ->count();

        $rejectedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Keuangan');
        })->where('status_disposisi', 'Ditolak')
            ->withTrashed()
            ->count();

        // Mengirim data ke view
        return view('keuangan.dashboard', compact(
            'pendingApprovals',
            'totalProposals',
            'approvedProposals',
            'rejectedProposals',
        ));
    }
}
