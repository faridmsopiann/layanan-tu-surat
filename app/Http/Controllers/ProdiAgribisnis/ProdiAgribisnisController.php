<?php

namespace App\Http\Controllers\ProdiAgribisnis;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiAgribisnisController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Agribisnis')
            ->count();

        // Mengirim data ke view
        return view('prodi-agribisnis.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
