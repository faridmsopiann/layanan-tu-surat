<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\ModalDisposisi;
use App\Models\PengajuanSurat;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengajuanSuratController extends Controller
{
    // Tampilkan halaman pengajuan surat
    public function index()
    {
        // Ambil semua proposal yang belum dihapus (soft delete)
        $proposals = Proposal::with(['pemohon', 'modalDisposisi'])
            ->where('pemohon_id', auth()->id())
            ->whereNull('deleted_at')
            ->paginate(5);

        return view('pemohon.proposals.index', compact('proposals'));
    }

    // Tampilkan form untuk membuat surat
    public function create()
    {
        return view('pemohon.proposals.create');
    }

    // Simpan pengajuan surat
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'required|string',
            'hal' => 'required|string',
            'soft_file' => 'nullable|mimes:pdf', // Validasi tipe file tanpa batasan ukuran
        ]);

        // Cek apakah ada file yang di-upload
        if ($request->hasFile('soft_file')) {
            $file = $request->file('soft_file');

            // Validasi ukuran file
            if ($file->getSize() > 614400) { // 300 KB = 307200 bytes
                return redirect()->back()->withErrors(['soft_file' => 'Ukuran file tidak boleh lebih dari 600 KB.'])->withInput();
            }

            $filePath = $file->store('proposals', 'public'); // Simpan file yang valid ke storage
        } else {
            $filePath = null; // Tidak ada file yang diunggah
        }

        // Ambil tahun dan bulan dari tanggal saat ini
        $tahun = now()->year;
        $bulan = now()->month;

        // Ambil nomor urut terakhir yang digunakan untuk kode_pengajuan
        $lastProposal = Proposal::withTrashed()
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

        $proposal = Proposal::create([
            'pemohon_id' => auth()->id(),
            'tanggal_surat' => $request->tanggal_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'kode_pengajuan' => $kodePengajuan,
            'jenis_proposal' => $request->jenis_proposal,
            'soft_file' => $filePath,
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => 'Staff TU',
            'status' => 'Diproses',
            'tanggal_diterima' => null,
            'tanggal_proses' => null,
            'diverifikasi_oleh' => null,
            'keterangan' => null,
        ]);

        return redirect()->route('pemohon.proposals.index')->with('success', 'Pengajuan surat berhasil ditambahkan.');
    }



    // Hapus pengajuan surat
    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Cek status proposal
        if (in_array($proposal->status_disposisi, ['Selesai', 'Ditolak'])) {
            // Hapus proposal dengan soft delete
            $proposal->delete();
            return redirect()->route('pemohon.proposals.index')->with('success', 'Pengajuan surat berhasil di-soft delete.');
        } else {
            // Hapus proposal secara permanen
            $proposal->forceDelete();
            return redirect()->route('pemohon.proposals.index')->with('success', 'Pengajuan surat berhasil dihapus secara permanen.');
        }
    }

    // Tampilkan detail surat dalam pop-up
    public function show($id)
    {
        $proposals = Proposal::findOrFail($id);
        // Mengambil modal disposisi yang terkait dengan proposal ini berdasarkan proposal_id
        $modal = ModalDisposisi::where('proposal_id', $id)->with('proposal')->get();

        return view('pemohon.proposals.detail', compact('proposals', 'modal'));
    }
}
