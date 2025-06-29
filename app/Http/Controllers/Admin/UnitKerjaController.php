<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $data = UnitKerja::all();
        return view('admin.unit-kerja.index', compact('data'));
    }

    public function create()
    {
        return view('admin.unit-kerja.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        UnitKerja::create($request->only('nama'));
        return redirect()->route('admin.unit-kerja.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(UnitKerja $unit_kerja)
    {
        return view('admin.unit-kerja.edit', compact('unit_kerja'));
    }

    public function update(Request $request, UnitKerja $unit_kerja)
    {
        $request->validate(['nama' => 'required']);
        $unit_kerja->update($request->only('nama'));
        return redirect()->route('admin.unit-kerja.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(UnitKerja $unit_kerja)
    {
        $unit_kerja->delete();
        return redirect()->route('admin.unit-kerja.index')->with('success', 'Berhasil dihapus');
    }
}
