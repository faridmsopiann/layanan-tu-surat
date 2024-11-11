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

    // Menyimpan proposal baru ke database
    public function store(Request $request)
    {
        // Validasi data input
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

        // Cek apakah ada file yang di-upload
        if ($request->hasFile('soft_file')) {
            $filePath = $request->file('soft_file')->store('proposals', 'public'); // menyimpan ke folder 'storage/app/public/proposals'
        } else {
            $filePath = null; // jika tidak ada file diunggah
        }

        // Ambil tahun dan bulan dari tanggal saat ini
        $tahun = now()->year;  // Ambil tahun saat ini
        $bulan = now()->month; // Ambil bulan saat ini

        // Ambil nomor urut terakhir yang digunakan untuk kode_pengajuan
        $lastProposal = Proposal::withTrashed() // Ambil semua proposal, termasuk yang dihapus
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->latest()
            ->first();
        $increment = 1;

        if ($lastProposal) {
            // Jika ada proposal sebelumnya, ambil nomor urut terakhir dan tambahkan 1
            $lastKode = substr($lastProposal->kode_pengajuan, -4);
            $increment = (int)$lastKode + 1;
        }

        // Format kode_pengajuan (misal: P2024211001)
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

    // Menampilkan form edit proposal
    public function edit($id)
    {
        $proposal = Proposal::withTrashed()->findOrFail($id);
        return view('admin.proposals.edit', compact('proposal'));
    }

    // Update proposal
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

    // Hapus proposal
    public function destroy($id)
    {
        // Mencari proposal dengan ID, termasuk yang di-soft delete
        $proposal = Proposal::withTrashed()->findOrFail($id);

        // Hapus proposal secara permanen
        $proposal->forceDelete();
        return redirect()->route('admin.proposals.index')->with('success', 'Proposal berhasil dihapus.');
    }
}
