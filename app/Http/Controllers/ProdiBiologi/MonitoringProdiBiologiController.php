<?php

namespace App\Http\Controllers\ProdiBiologi;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiBiologiController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Biologi');
        })->paginate(4);

        return view('prodi-biologi.monitoring.index', compact('proposals'));
    }
}
