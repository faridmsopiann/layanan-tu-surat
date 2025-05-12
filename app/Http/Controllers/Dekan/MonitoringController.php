<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->whereIn('tujuan', [
                'Dekan',
                'Wadek Akademik',
                'Wadek Kemahasiswaan',
                'Wadek Administrasi Umum'
            ]);
        })->paginate(4);

        return view('dekan.monitoring.index', compact('proposals'));
    }
}
