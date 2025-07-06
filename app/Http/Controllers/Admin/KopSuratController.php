<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KopSurat;
use Illuminate\Http\Request;

class KopSuratController extends Controller
{
    public function index()
    {
        $data = KopSurat::latest()->get();
        return view('admin.kop-surat.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $data = ['nama' => $request->nama];

        if ($request->hasFile('kop_surat')) {
            $file = $request->file('kop_surat');
            $filename = 'kop_surat_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['kop_surat'] = 'images/' . $filename;
        }

        if ($request->hasFile('footer')) {
            $file = $request->file('footer');
            $filename = 'footer_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['footer'] = 'images/' . $filename;
        }

        KopSurat::create($data);
        return redirect()->route('admin.kop-surat.index')->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, KopSurat $kopSurat)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $data = ['nama' => $request->nama];

        if ($request->hasFile('kop_surat')) {
            $file = $request->file('kop_surat');
            $filename = 'kop_surat_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['kop_surat'] = 'images/' . $filename;
        }

        if ($request->hasFile('footer')) {
            $file = $request->file('footer');
            $filename = 'footer_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['footer'] = 'images/' . $filename;
        }

        $kopSurat->update($data);

        return redirect()->route('admin.kop-surat.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(KopSurat $kopSurat)
    {
        $kopSurat->delete();
        return back()->with('success', 'Data dihapus');
    }
}
