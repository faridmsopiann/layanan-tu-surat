<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class UmumController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Umum')
            ->count();

        // Mengirim data ke view
        return view('umum.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
