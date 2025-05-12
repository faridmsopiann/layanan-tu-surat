<?php

namespace App\Http\Controllers\ProdiKimia;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiKimiaController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Kimia')
            ->count();

        // Mengirim data ke view
        return view('prodi-kimia.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
