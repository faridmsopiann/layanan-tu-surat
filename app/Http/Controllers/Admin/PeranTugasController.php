<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeranTugas;
use Illuminate\Http\Request;

class PeranTugasController extends Controller
{
    public function index()
    {
        $data = PeranTugas::all();
        return view('admin.peran-tugas.index', compact('data'));
    }

    public function create()
    {
        return view('admin.peran-tugas.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        PeranTugas::create($request->only('nama'));
        return redirect()->route('admin.peran-tugas.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(PeranTugas $peran_tuga)
    {
        return view('admin.peran-tugas.edit', ['peran_tugas' => $peran_tuga]);
    }

    public function update(Request $request, PeranTugas $peran_tuga)
    {
        $request->validate(['nama' => 'required']);
        $peran_tuga->update($request->only('nama'));
        return redirect()->route('admin.peran-tugas.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(PeranTugas $peran_tuga)
    {
        $peran_tuga->delete();
        return redirect()->route('admin.peran-tugas.index')->with('success', 'Berhasil dihapus');
    }
}
