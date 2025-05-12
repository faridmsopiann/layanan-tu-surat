<?php

namespace App\Http\Controllers\ProdiFisika;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiFisikaController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Fisika')
            ->count();

        // Mengirim data ke view
        return view('prodi-fisika.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
