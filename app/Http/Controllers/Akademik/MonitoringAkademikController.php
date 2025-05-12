<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringAkademikController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Akademik');
        })->paginate(4);

        return view('akademik.monitoring.index', compact('proposals'));
    }
}
