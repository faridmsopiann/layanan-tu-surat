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
        $userCount = User::count();

        $proposalCount = Proposal::withTrashed()->count();

        $pendingApprovals = Proposal::whereNotIn('status_disposisi', ['Selesai', 'Ditolak'])->count();

        $rejectedProposals = Proposal::where('status_disposisi', 'Ditolak')->count();

        return view('admin.dashboard', compact('userCount', 'proposalCount', 'pendingApprovals', 'rejectedProposals'));
    }
}
