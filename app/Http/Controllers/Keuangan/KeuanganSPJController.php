<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class KeuanganSPJController extends Controller
{
    public function index()
    {
        $spjs = Spj::query()
            ->with(['proposal', 'user'])
            ->paginate(5);

        return view('keuangan.spj.index', compact('spjs'));
    }

    public function show($id)
    {
        $spj = Spj::with(['proposal', 'user', 'documents.category'])->where('id', $id)->first();
        return view('keuangan.spj.show', compact('spj'));
    }

    public function edit($id, Request $request)
    {
        $spj = Spj::find($id);
        $spj->update([
            'status' => $request->type == 'revisi' ? 'Revisi' : 'Selesai',
            'catatan' => $request->catatan,
            'tanggal_selesai' => $request->type == 'setuju' ? now() : null,
        ]);

        return redirect()->route('keuangan.spj.index')->with('success', 'Status pengajuan berhasil diubah.');
    }
}
