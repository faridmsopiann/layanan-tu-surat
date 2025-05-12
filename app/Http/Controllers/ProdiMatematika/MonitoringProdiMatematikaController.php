<?php

namespace App\Http\Controllers\ProdiMatematika;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class MonitoringProdiMatematikaController extends Controller
{
    public function index()
    {
        $proposals = Proposal::whereHas('modalDisposisi', function ($query) {
            $query->where('tujuan', 'Prodi Matematika');
        })->paginate(4);

        return view('prodi-matematika.monitoring.index', compact('proposals'));
    }
}
