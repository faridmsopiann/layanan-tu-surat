<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $data = Jabatan::all();
        return view('admin.jabatan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        Jabatan::create($request->only('nama'));
        return redirect()->route('admin.jabatan.index')->with('success', 'Berhasil ditambahkan');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate(['nama' => 'required']);
        $jabatan->update($request->only('nama'));
        return redirect()->route('admin.jabatan.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('admin.jabatan.index')->with('success', 'Berhasil dihapus');
    }

}
