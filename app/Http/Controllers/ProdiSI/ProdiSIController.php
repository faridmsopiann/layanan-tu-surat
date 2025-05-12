<?php

namespace App\Http\Controllers\ProdiSI;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiSIController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Sistem Informasi')
            ->count();

        // Mengirim data ke view
        return view('prodi-si.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
