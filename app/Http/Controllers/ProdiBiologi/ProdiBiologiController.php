<?php

namespace App\Http\Controllers\ProdiBiologi;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiBiologiController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Biologi')
            ->count();

        // Mengirim data ke view
        return view('prodi-biologi.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
