<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    // Menampilkan semua proposal untuk disposisi oleh Dekan
    public function index()
    {
        $proposals = Proposal::where('status_disposisi', 'Menunggu Approval Dekan')
            ->orWhereIn('tujuan_disposisi', ['Dekan', 'Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])
            ->paginate(5);
        return view('dekan.disposisi.index', compact('proposals'));
    }

    // Menampilkan form disposisi untuk proposal
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('dekan.disposisi.disposisi_form', compact('proposal'));
    }

    // Menampilkan form reject untuk proposal
    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('dekan.disposisi.reject_form', compact('proposal'));
    }

    // Menyimpan disposisi proposal
    public function updateDisposisi(Request $request, Proposal $proposal)
    {
        $request->validate([
            'pesan_disposisi' => 'required|string|max:255',
            'dari' => 'required|string',
        ]);

        $status_disposisi = '';
        if ($request->disposisi == 'Kabag TU') {
            $status_disposisi = 'Menunggu Approval Kabag';
        } elseif (in_array($request->disposisi, ['Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])) {
            $status_disposisi = 'Menunggu Approval ' . $request->disposisi;
        }

        // Update status dan tujuan disposisi ke Wadek
        $proposal->update([
            'dari' => $request->dari,
            'tujuan_disposisi' => $request->disposisi,
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => $status_disposisi,
        ]);

        if ($request->dari == 'Dekan') {
            // Ambil modal_disposisi yang terkait dengan proposal
            $modal = ModalDisposisi::where('proposal_id', $proposal->id)
                ->where('tujuan', 'Dekan')
                ->first();
        } elseif (in_array($request->dari, ['Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])) {
            $modal = ModalDisposisi::where('proposal_id', $proposal->id)
                ->whereIn('tujuan', ['Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])
                ->first();
        }

        $modal->update([
            'tujuan' => $request->dari,  // Update tujuan dari request
            'status' => 'Disetujui',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now()->format('Y-m-d H:i:s'),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => $request->dari,  // Nama user yang sedang login
            'keterangan' => $request->pesan_disposisi,  // Pesan dari request
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => $request->disposisi,
            'status' => 'Diproses',
            'tanggal_diterima' => now()->format('Y-m-d H:i:s'),
            'tanggal_proses' => null,
            'diverifikasi_oleh' => null,
            'keterangan' => null,
        ]);

        return redirect()->route('disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
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
            'tujuan_disposisi' => 'Staff Tata Usaha',
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => 'Ditolak',
        ]);

        if ($request->dari == 'Dekan') {
            // Ambil modal_disposisi yang terkait dengan proposal
            $modal = ModalDisposisi::where('proposal_id', $proposal->id)
                ->where('tujuan', 'Dekan')
                ->first();
        } elseif (in_array($request->dari, ['Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])) {
            $modal = ModalDisposisi::where('proposal_id', $proposal->id)
                ->whereIn('tujuan', ['Wadek Akademik', 'Wadek Kemahasiswaan', 'Wadek Administrasi Umum'])
                ->first();
        }

        $modal->update([
            'tujuan' => $request->dari,  // Update tujuan dari request
            'status' => 'Ditolak',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now()->format('Y-m-d H:i:s'),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => $request->dari,  // Nama user yang sedang login
            'keterangan' => $request->pesan_disposisi,  // Pesan dari request
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => 'Staff TU',
            'status' => 'Ditolak',
            'tanggal_diterima' => now()->format('Y-m-d H:i:s'),
            'tanggal_proses' => now()->format('Y-m-d H:i:s'),
            'diverifikasi_oleh' => 'Bu Susi',
            'keterangan' => 'Selesai',
        ]);

        return redirect()->route('disposisi.index')->with('success', 'Proposal berhasil ditolak.');
    }
}
