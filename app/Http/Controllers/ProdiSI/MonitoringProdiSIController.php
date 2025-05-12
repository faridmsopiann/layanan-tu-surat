<?php

namespace App\Http\Controllers\ProdiSI;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiSIController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Teknik Informatika');
        })->paginate(4);

        return view('prodi-si.monitoring.index', compact('proposals'));
    }
}
