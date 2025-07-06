<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\PegawaiPenugasan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class PegawaiPenugasanController extends Controller
{
    public function index()
    {
        $data = PegawaiPenugasan::with('unit')->with('jabatan')->get();
        $unitKerja = UnitKerja::all();
        $jabatans = Jabatan::all(); 
        return view('admin.pegawai-penugasan.index', compact('data', 'unitKerja', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'jabatan_id' => 'nullable|exists:jabatans,id',
        ]);

        PegawaiPenugasan::create($request->only('nama', 'nip', 'unit_id', 'jabatan_id'));
        return redirect()->route('admin.pegawai-penugasan.index')->with('success', 'Berhasil ditambahkan');
    }

    public function update(Request $request, PegawaiPenugasan $pegawai_penugasan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'unit_id' => 'nullable|exists:units,id',
            'jabatan_id' => 'nullable|exists:jabatans,id',
        ]);

        $pegawai_penugasan->update($request->only('nama', 'nip', 'unit_id', 'jabatan_id'));
        return redirect()->route('admin.pegawai-penugasan.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(PegawaiPenugasan $pegawai_penugasan)
    {
        $pegawai_penugasan->delete();
        return redirect()->route('admin.pegawai-penugasan.index')->with('success', 'Berhasil dihapus');
    }
}
