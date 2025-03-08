<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\ModalDisposisi;
use App\Models\PengajuanSurat;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ZipArchive;

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

    public function downloadZip($id)
    {
        $proposal = Proposal::findOrFail($id);
        $files = json_decode($proposal->soft_file, true);

        if (!$files || count($files) == 0) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada file untuk diunduh.']);
        }

        // Buat nama file ZIP
        $zipFileName = 'proposal_' . $proposal->id . '.zip';
        $zipPath = storage_path('app/public/proposals/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($file));
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // Simpan pengajuan surat
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'required|string',
            'hal' => 'required|string',
            'soft_file.*' => 'nullable|file|max:10240', // Bisa lebih dari satu file (10 MB per file)
            'file_link' => 'nullable|url', // Validasi link jika diisi
        ]);

        $softFilePaths = [];
        $softFileLink = $request->input('file_link'); // Simpan link jika ada

        if ($request->hasFile('soft_file')) {
            foreach ($request->file('soft_file') as $file) {
                $path = $file->store('proposals', 'public'); // Simpan file ke storage
                $softFilePaths[] = $path;
            }
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
            $lastKode = substr($lastProposal->kode_pengajuan, -4);
            $increment = (int)$lastKode + 1;
        }

        // Format kode_pengajuan (misal: P2024311001)
        $kodePengajuan = 'P' . $tahun . $bulan . str_pad($increment, 4, '0', STR_PAD_LEFT);

        // Hitung jumlah proposal dalam tahun ini yang tidak dihapus (soft delete)
        $nomorAgenda = Proposal::withTrashed()
            ->whereYear('created_at', $tahun)
            ->count() + 1;

        $proposal = Proposal::create([
            'pemohon_id' => auth()->id(),
            'tanggal_surat' => $request->tanggal_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'kode_pengajuan' => $kodePengajuan,
            'soft_file' => count($softFilePaths) > 0 ? json_encode($softFilePaths) : null,
            'soft_file_link' => $softFileLink,
            'nomor_agenda' => $nomorAgenda,
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


    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'required|string',
            'hal' => 'required|string',
            'soft_file.*' => 'nullable|file|max:10240',
            'file_link' => 'nullable|url',
        ]);

        $proposal = Proposal::findOrFail($id);

        if ($request->hasFile('soft_file')) {
            $filePaths = [];
            foreach ($request->file('soft_file') as $file) {
                $filePaths[] = $file->store('proposals', 'public');
            }
            $proposal->soft_file = json_encode($filePaths); // Simpan dalam format JSON
        }

        $proposal->update([
            'tanggal_surat' => $request->tanggal_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'soft_file_link' => $request->file_link,
        ]);

        return redirect()->back()->with('success', 'Proposal berhasil diperbarui.');
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
