<?php

namespace App\Http\Controllers\ProdiFisika;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiFisikaController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Fisika');
        })->paginate(4);

        return view('prodi-fisika.monitoring.index', compact('proposals'));
    }
}
