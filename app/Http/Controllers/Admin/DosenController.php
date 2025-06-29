<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $data = Dosen::with('unit')->get();
        $unitKerja = UnitKerja::all();
        return view('admin.dosen.index', compact('data', 'unitKerja'));
    }

    public function create()
    {
        $units = UnitKerja::all();
        return view('admin.dosen.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:unit_kerja,id',
        ]);

        Dosen::create($request->only('nama', 'nip', 'unit_id'));
        return redirect()->route('admin.dosen.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Dosen $dosen)
    {
        $units = UnitKerja::all();
        return view('admin.dosen.edit', compact('dosen', 'units'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'unit_id' => 'nullable|exists:unit_kerja,id',
        ]);

        $dosen->update($request->only('nama', 'nip', 'unit_id'));
        return redirect()->route('admin.dosen.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('admin.dosen.index')->with('success', 'Berhasil dihapus');
    }
}
