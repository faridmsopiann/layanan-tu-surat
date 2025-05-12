<?php

namespace App\Http\Controllers\ProdiAgribisnis;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiAgribisnisController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Agribisnis');
        })->paginate(4);

        return view('prodi-agribisnis.monitoring.index', compact('proposals'));
    }
}
