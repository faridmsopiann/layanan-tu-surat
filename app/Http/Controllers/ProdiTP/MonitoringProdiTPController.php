<?php

namespace App\Http\Controllers\ProdiTP;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiTPController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Teknik Pertambangan');
        })->paginate(4);

        return view('prodi-tp.monitoring.index', compact('proposals'));
    }
}
