<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil data proposal untuk ditampilkan di halaman monitoring
        $proposals = Proposal::whereIn('dari', [
            'Dekan',
            'Wadek Akademik',
            'Wadek Kemahasiswaan',
            'Wadek Administrasi Umum'
        ])->paginate(5);

        return view('dekan.monitoring.index', compact('proposals'));
    }
}
