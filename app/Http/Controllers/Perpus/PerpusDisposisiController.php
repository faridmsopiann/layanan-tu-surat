<?php

namespace App\Http\Controllers\Perpus;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PerpusDisposisiController extends Controller
{
    // Menampilkan semua proposal untuk disposisi oleh Dekan
    public function index()
    {
        $proposals = Proposal::where('status_disposisi', 'Menunggu Approval Perpus')
            ->orWhere('tujuan_disposisi', 'Prodi')
            ->paginate(5);
        return view('perpus.disposisi.index', compact('proposals'));
    }

    // Menampilkan form disposisi untuk proposal
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('perpus.disposisi.disposisi_form', compact('proposal'));
    }

    // Menampilkan form reject untuk proposal
    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('perpus.disposisi.reject_form', compact('proposal'));
    }

    // Menyimpan disposisi proposal
    public function updateDisposisi(Request $request, Proposal $proposal)
    {
        $request->validate([
            'pesan_disposisi' => 'required|string|max:255',
            'dari' => 'required|string',
        ]);

        $status_disposisi = '';
        if ($request->disposisi == 'Staff TU') {
            $status_disposisi = 'Selesai';
        }

        // Update status dan tujuan disposisi ke Kabag TU
        $proposal->update([
            'dari' => $request->dari,
            'tujuan_disposisi' => $request->disposisi,
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => $status_disposisi,
        ]);

        // Ambil modal_disposisi yang terkait dengan proposal
        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Perpus')
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
            'diverifikasi_oleh' => 'Susi Sundari. SE',
            'keterangan' => 'Selesai',
        ]);

        // if ($request->disposisi == 'Keuangan') {
        //     ModalDisposisi::create([
        //         'proposal_id' => $proposal->id,
        //         'tujuan' => $request->disposisi,
        //         'status' => 'Diproses',
        //         'tanggal_diterima' => now(),
        //         'tanggal_proses' => null,
        //         'diverifikasi_oleh' => null,
        //         'keterangan' => null,
        //     ]);
        // } else {
        //     ModalDisposisi::create([
        //         'proposal_id' => $proposal->id,
        //         'tujuan' => 'Staff TU',
        //         'status' => 'Disetujui',
        //         'tanggal_diterima' => now(),
        //         'tanggal_proses' => now(),
        //         'diverifikasi_oleh' => 'Bu Susi',
        //         'keterangan' => 'Selesai',
        //     ]);
        // }

        return redirect()->route('perpus.disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
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
            'dari' => $request->dari,
            'tujuan_disposisi' => $request->disposisi,
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => 'Ditolak',
        ]);

        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Perpus')
            ->first();

        $modal->update([
            'tujuan' => 'Perpus',  // Update tujuan dari request
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
            'diverifikasi_oleh' => 'Susi Sundari. SE',
            'keterangan' => 'Selesai',
        ]);

        return redirect()->route('perpus.disposisi.index')->with('success', 'Proposal berhasil ditolak.');
    }
}
