<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringTUController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->whereIn('tujuan', [
                'Staff TU',
                'Kabag TU',
            ]);
        })->paginate(4);

        return view('tu.monitoring.index', compact('proposals'));
    }
}
