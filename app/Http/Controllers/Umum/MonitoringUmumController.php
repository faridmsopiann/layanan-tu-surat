<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringUmumController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Umum');
        })->paginate(4);

        return view('umum.monitoring.index', compact('proposals'));
    }
}
