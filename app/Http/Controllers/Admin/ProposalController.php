<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;

class ProposalController extends Controller
{

    // Menampilkan semua proposal
    public function index()
    {
        $proposals = Proposal::withTrashed()->paginate(4);
        return view('admin.proposals.index', compact('proposals'));
    }

    // Menampilkan form create proposal
    public function create()
    {
        return view('admin.proposals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_agenda' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'required|string|max:255',
            'asal_surat' => 'required|string|max:255',
            'hal' => 'required|string|max:255',
            'diterima_tanggal' => 'required|date',
            'untuk' => 'required|string|max:255',
            'status_disposisi' => 'required|string|max:255',
            'soft_file' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('soft_file')) {
            $filePath = $request->file('soft_file')->store('proposals', 'public'); 
        } else {
            $filePath = null; 
        }

        $tahun = now()->year; 
        $bulan = now()->month; 

        $lastProposal = Proposal::withTrashed() 
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->latest()
            ->first();
        $increment = 1;

        if ($lastProposal) {
            $lastKode = substr($lastProposal->kode_pengajuan, -4);
            $increment = (int)$lastKode + 1;
        }

        $kodePengajuan = 'P' . $tahun . $bulan . str_pad($increment, 4, '0', STR_PAD_LEFT);

        Proposal::create([
            'pemohon_id' => auth()->id(),
            'nomor_agenda' => $request->nomor_agenda,
            'tanggal_surat' => $request->tanggal_surat,
            'nomor_surat' => $request->nomor_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'diterima_tanggal' => $request->diterima_tanggal,
            'untuk' => $request->untuk,
            'jenis_proposal' => $request->jenis_proposal,
            'status_disposisi' => $request->status_disposisi,
            'soft_file' => $filePath,
            'kode_pengajuan' => $kodePengajuan,
        ]);
        return redirect()->route('admin.proposals.index')->with('success', 'Proposal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $proposal = Proposal::withTrashed()->findOrFail($id);
        return view('admin.proposals.edit', compact('proposal'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $request->validate([
            'nomor_agenda' => 'required',
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'required',
            'asal_surat' => 'required',
            'hal' => 'required',
            'diterima_tanggal' => 'required|date',
            'untuk' => 'required',
            'status_disposisi' => 'required',
            'soft_file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $proposal->update($request->all());
        return redirect()->route('admin.proposals.index')->with('success', 'Proposal berhasil diupdate.');
    }

    public function destroy($id)
    {
        $proposal = Proposal::withTrashed()->findOrFail($id);

        $proposal->forceDelete();
        return redirect()->route('admin.proposals.index')->with('success', 'Proposal berhasil dihapus.');
    }
}
