<?php

namespace App\Http\Controllers\Plt;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PltController extends Controller
{
    public function dashboard()
    {

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval PLT')
            ->count();

        // Mengirim data ke view
        return view('plt.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
