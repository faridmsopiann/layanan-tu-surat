<?php

namespace App\Http\Controllers\ProdiTI;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiTIController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Teknik Informatika')
            ->count();

        // Mengirim data ke view
        return view('prodi-ti.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
