<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil data proposal untuk ditampilkan di halaman monitoring
        $proposals = Proposal::where('dari', 'Staff Keuangan')
            ->whereIn('status_disposisi', ['Selesai', 'Ditolak'])
            ->paginate(5);

        return view('keuangan.monitoring.index', compact('proposals'));
    }
}
