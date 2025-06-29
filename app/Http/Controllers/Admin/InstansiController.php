<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index()
    {
        $data = Instansi::all();
        return view('admin.instansi.index', compact('data'));
    }

    public function create()
    {
        return view('admin.instansi.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        Instansi::create($request->only('nama'));
        return redirect()->route('admin.instansi.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Instansi $instansi)
    {
        return view('admin.instansi.edit', compact('instansi'));
    }

    public function update(Request $request, Instansi $instansi)
    {
        $request->validate(['nama' => 'required']);
        $instansi->update($request->only('nama'));
        return redirect()->route('admin.instansi.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Instansi $instansi)
    {
        $instansi->delete();
        return redirect()->route('admin.instansi.index')->with('success', 'Berhasil dihapus');
    }
}
