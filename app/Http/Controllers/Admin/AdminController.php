<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Menghitung total user dari tabel users
        $userCount = User::count();

        // Menghitung total proposal, termasuk yang dihapus (trashed)
        $proposalCount = Proposal::withTrashed()->count();

        // Menghitung pending approvals (selain status "Selesai" dan "Ditolak")
        $pendingApprovals = Proposal::whereNotIn('status_disposisi', ['Selesai', 'Ditolak'])->count();

        // Menghitung rejected proposals (status "Ditolak")
        $rejectedProposals = Proposal::where('status_disposisi', 'Ditolak')->count();

        return view('admin.dashboard', compact('userCount', 'proposalCount', 'pendingApprovals', 'rejectedProposals'));
    }
}
