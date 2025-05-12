<?php

namespace App\Http\Controllers\ProdiTI;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiTIController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Teknik Informatika');
        })->paginate(4);

        return view('prodi-ti.monitoring.index', compact('proposals'));
    }
}
