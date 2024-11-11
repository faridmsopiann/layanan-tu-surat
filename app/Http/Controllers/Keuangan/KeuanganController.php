<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id(); // Mendapatkan ID pemohon yang sedang login

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::where('status_disposisi', 'Menunggu Approval Keuangan')
            ->count();

        // Mengirim data ke view
        return view('keuangan.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
