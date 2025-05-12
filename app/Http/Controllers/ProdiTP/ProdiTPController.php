<?php

namespace App\Http\Controllers\ProdiTP;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiTPController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Teknik Pertambangan')
            ->count();

        // Mengirim data ke view
        return view('prodi-tp.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
