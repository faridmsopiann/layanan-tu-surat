<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisKegiatan;
use Illuminate\Http\Request;

class JenisKegiatanController extends Controller
{
    public function index()
    {
        $data = JenisKegiatan::all();
        return view('admin.jenis-kegiatan.index', compact('data'));
    }

    public function create()
    {
        return view('admin.jenis-kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        JenisKegiatan::create($request->only('nama'));
        return redirect()->route('admin.jenis-kegiatan.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(JenisKegiatan $jenis_kegiatan)
    {
        return view('admin.jenis-kegiatan.edit', compact('jenis_kegiatan'));
    }

    public function update(Request $request, JenisKegiatan $jenis_kegiatan)
    {
        $request->validate(['nama' => 'required']);
        $jenis_kegiatan->update($request->only('nama'));
        return redirect()->route('admin.jenis-kegiatan.index')->with('success', 'Berhasil diperbarui');
    }

    public function destroy(JenisKegiatan $jenis_kegiatan)
    {
        $jenis_kegiatan->delete();
        return redirect()->route('admin.jenis-kegiatan.index')->with('success', 'Berhasil dihapus');
    }
}
