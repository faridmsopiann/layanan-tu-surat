<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class DekanController extends Controller
{
    public function dashboard()
    {
        $totalProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->whereIn('tujuan', [
                'Dekan',
                'Wadek Akademik',
                'Wadek Kemahasiswaan',
                'Wadek Administrasi Umum'
            ]);
        })->withTrashed()->count();

        $pendingApprovals = Proposal::whereIn('status_disposisi', ['Menunggu Approval Dekan', 'Menunggu Approval Wadek Akademik', 'Menunggu Approval Wadek Kemahasiswaan', 'Menunggu Approval Wadek Administrasi Umum'])
            ->count();

        $approvedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->whereIn('tujuan', [
                'Dekan',
                'Wadek Akademik',
                'Wadek Kemahasiswaan',
                'Wadek Administrasi Umum'
            ]);
        })
            ->where('status_disposisi', 'Selesai')
            ->withTrashed()
            ->count();

        $rejectedProposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->whereIn('tujuan', [
                'Dekan',
                'Wadek Akademik',
                'Wadek Kemahasiswaan',
                'Wadek Administrasi Umum'
            ]);
        })->where('status_disposisi', 'Ditolak')
            ->withTrashed()
            ->count();

        return view('dekan.dashboard', compact(
            'totalProposals',
            'approvedProposals',
            'pendingApprovals',
            'rejectedProposals',
        ));
    }
}
