<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;

class KeuanganDisposisiController extends Controller
{
    // Menampilkan semua proposal untuk disposisi oleh Dekan
    public function index()
    {
        $proposals = Proposal::where('status_disposisi', 'Menunggu Approval Keuangan')
            ->orWhere('tujuan_disposisi', 'Keuangan')
            ->paginate(5);
        return view('keuangan.disposisi.index', compact('proposals'));
    }

    // Menampilkan form disposisi untuk proposal
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('keuangan.disposisi.disposisi_form', compact('proposal'));
    }

    // Menampilkan form reject untuk proposal
    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('keuangan.disposisi.reject_form', compact('proposal'));
    }

    // Menyimpan disposisi proposal
    public function updateDisposisi(Request $request, Proposal $proposal)
    {
        $request->validate([
            'pesan_disposisi' => 'required|string|max:255',
            'dari' => 'required|string',
        ]);

        // Update status dan tujuan disposisi ke Kabag TU
        $proposal->update([
            'dari' => 'Staff Keuangan',
            'tujuan_disposisi' => 'Staff Tata Usaha',
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => 'Selesai',
        ]);

        // Ambil modal_disposisi yang terkait dengan proposal
        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Keuangan')
            ->first();


        $modal->update([
            'tujuan' => $request->dari,  // Update tujuan dari request
            'status' => 'Disetujui',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now(),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => auth()->user()->name,  // Nama user yang sedang login
            'keterangan' => $request->pesan_disposisi,  // Pesan dari request
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => 'Staff TU',
            'status' => 'Disetujui',
            'tanggal_diterima' => now(),
            'tanggal_proses' => now(),
            'diverifikasi_oleh' => 'Bu Susi',
            'keterangan' => 'Selesai',
        ]);

        return redirect()->route('keuangan.disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
    }

    // Menyimpan reject proposal
    public function updateReject(Request $request, Proposal $proposal)
    {
        $request->validate([
            'dari' => 'required|string',
            'pesan_disposisi' => 'required|string|max:255',
        ]);

        // Update status menjadi Ditolak
        $proposal->update([
            'dari' => 'Staff Keuangan',
            'tujuan_disposisi' => 'Staff Tata Usaha',
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => 'Ditolak',
        ]);

        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Keuangan')
            ->first();

        $modal->update([
            'tujuan' => 'Keuangan',  // Update tujuan dari request
            'status' => 'Ditolak',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now(),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => auth()->user()->name,  // Nama user yang sedang login
            'keterangan' => $request->pesan_disposisi,  // Pesan dari request
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => 'Staff TU',
            'status' => 'Ditolak',
            'tanggal_diterima' => now(),
            'tanggal_proses' => now(),
            'diverifikasi_oleh' => 'Bu Susi',
            'keterangan' => 'Selesai',
        ]);

        return redirect()->route('keuangan.disposisi.index')->with('success', 'Proposal berhasil ditolak.');
    }
}
