<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class UmumController extends Controller
{
    public function dashboard()
    {

        $totalProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Umum');
        })->withTrashed()->count();

        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Umum')
            ->count();

        $approvedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Umum');
        })
            ->where('status_disposisi', 'Selesai')
            ->withTrashed()
            ->count();

        $rejectedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Umum');
        })->where('status_disposisi', 'Ditolak')
            ->withTrashed()
            ->count();

        // Mengirim data ke view
        return view('umum.dashboard', compact(
            'pendingApprovals',
            'totalProposals',
            'approvedProposals',
            'rejectedProposals',
        ));
    }
}
