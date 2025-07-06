<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PejabatPenandatangan;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class PejabatPenandatanganController extends Controller
{
    public function index()
    {
        $data = PejabatPenandatangan::with('jabatan')->latest()->get();
        $jabatans = \App\Models\Jabatan::all(); // ambil semua jabatan
        return view('admin.pejabat-penandatangan.index', compact('data', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['nama', 'nip', 'jabatan_id']);

        if ($request->hasFile('ttd')) {
            $file = $request->file('ttd');
            $filename = 'ttd_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['ttd'] = 'images/' . $filename;
        }

        PejabatPenandatangan::create($data);

        return redirect()->route('admin.pejabat-penandatangan.index')->with('success', 'Pejabat berhasil ditambahkan.');
    }

    public function update(Request $request, PejabatPenandatangan $pejabat_penandatangan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
            'ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['nama', 'nip', 'jabatan_id']);

        if ($request->hasFile('ttd')) {
            $file = $request->file('ttd');
            $filename = 'ttd_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['ttd'] = 'images/' . $filename;
        }

        $pejabat_penandatangan->update($data);

        return redirect()->route('admin.pejabat-penandatangan.index')->with('success', 'Pejabat berhasil diperbarui.');
    }

    public function destroy(PejabatPenandatangan $pejabat_penandatangan)
    {
        $pejabat_penandatangan->delete();
        return back()->with('success', 'Pejabat dihapus.');
    }
}
