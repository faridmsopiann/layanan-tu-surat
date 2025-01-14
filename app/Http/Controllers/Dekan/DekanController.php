<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class DekanController extends Controller
{
    public function dashboard()
    {
        // $userId = auth()->id(); // Mendapatkan ID pemohon yang sedang login

        // // Menghitung jumlah proposal yang diajukan oleh pemohon
        // $totalProposals = Proposal::where('status_disposisi', 'Menunggu Approval Dekan')
        //     ->OrwhereIn('dari', ['Dekan', 'Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])
        //     ->count();

        // Menghitung proposal dengan status_disposisi selain 'Selesai' atau 'Ditolak'
        $pendingApprovals = Proposal::whereIn('status_disposisi', ['Menunggu Approval Dekan', 'Menunggu Approval Wadek Akademik', 'Menunggu Approval Wadek Kemahasiswaan', 'Menunggu Approval Wadek Administrasi Umum'])
            ->count();

        // // Menghitung proposal yang status_disposisinya 'Selesai'
        // $approvedProposals = Proposal::whereIn('dari', ['Dekan', 'Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])
        //     ->where('status_disposisi', 'Menunggu Approval Kabag')
        //     ->count();

        // // Menghitung proposal yang status_disposisinya 'Ditolak'
        // $rejectedProposals = Proposal::whereIn('dari', ['Dekan', 'Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])
        //     ->where('status_disposisi', 'Ditolak')
        //     ->count();

        // Mengirim data ke view
        return view('dekan.dashboard', compact(
            'pendingApprovals',
        ));
    }
}
