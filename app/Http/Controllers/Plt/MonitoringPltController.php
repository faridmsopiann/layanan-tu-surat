<?php

namespace App\Http\Controllers\Plt;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringPltController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'PLT');
        })->paginate(4);

        return view('plt.monitoring.index', compact('proposals'));
    }
}
