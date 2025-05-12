<?php

namespace App\Http\Controllers\ProdiKimia;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiKimiaController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Kimia');
        })->paginate(4);

        return view('prodi-kimia.monitoring.index', compact('proposals'));
    }
}
