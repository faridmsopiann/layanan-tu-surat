<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $proposals = Proposal::where('pemohon_id', $user->id)->latest()->paginate(4); // sesuaikan dengan kolom pemilik

        return view('tracking.index', compact('proposals'));
    }
}
