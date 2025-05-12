<?php

namespace App\Http\Controllers\ProdiMatematika;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProdiMatematikaController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Matematika')
            ->count();

        // Mengirim data ke view
        return view('prodi-matematika.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
