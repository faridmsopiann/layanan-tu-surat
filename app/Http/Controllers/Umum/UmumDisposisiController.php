<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;
use ZipArchive;

class UmumDisposisiController extends Controller
{
    // Menampilkan semua proposal untuk disposisi oleh Dekan
    public function index()
    {
        $proposals = Proposal::where('status_disposisi', 'Menunggu Approval Umum')
            ->orWhere('tujuan_disposisi', 'Prodi')
            ->paginate(5);
        return view('umum.disposisi.index', compact('proposals'));
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

    // Menampilkan form disposisi untuk proposal
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('umum.disposisi.disposisi_form', compact('proposal'));
    }

    // Menampilkan form reject untuk proposal
    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('umum.disposisi.reject_form', compact('proposal'));
    }

    // Menyimpan disposisi proposal
    public function updateDisposisi(Request $request, Proposal $proposal)
    {
        $request->validate([
            'pesan_disposisi' => 'required|string|max:255',
            'dari' => 'required|string',
        ]);

        $status_disposisi = $this->getStatusDisposisi($request->disposisi);

        // Update status dan tujuan disposisi ke Kabag TU
        $proposal->update([
            'dari' => $request->dari,
            'tujuan_disposisi' => $request->disposisi,
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => $status_disposisi,
        ]);

        // Ambil modal_disposisi yang terkait dengan proposal
        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Umum')
            ->where('status', 'Diproses')
            ->first();


        $modal->update([
            'tujuan' => $request->dari,  // Update tujuan dari request
            'status' => 'Disetujui',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now(),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => auth()->user()->name,  // Nama user yang sedang login
            'keterangan' => $request->pesan_disposisi,  // Pesan dari request
        ]);

        if ($request->disposisi == 'Staff TU') {
            $proposal->update([
                'dari' => $request->dari,
                'tujuan_disposisi' => 'Staff Tata Usaha',
                'pesan_disposisi' => $request->pesan_disposisi,
                'status_disposisi' => 'Selesai',
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
        } else {
            ModalDisposisi::create([
                'proposal_id' => $proposal->id,
                'tujuan' => $request->disposisi,
                'status' => 'Diproses',
                'tanggal_diterima' => now()->format('Y-m-d H:i:s'),
                'tanggal_proses' => null,
                'diverifikasi_oleh' => null,
                'keterangan' => null,
            ]);
        }

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

        return redirect()->route('umum.disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
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
            ->where('tujuan', 'Umum')
            ->first();

        $modal->update([
            'tujuan' => 'Umum',  // Update tujuan dari request
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

        return redirect()->route('umum.disposisi.index')->with('success', 'Proposal berhasil ditolak.');
    }

    private function getStatusDisposisi($tujuan)
    {
        // Status disposisi untuk Keuangan
        if ($tujuan == 'Keuangan') {
            return 'Menunggu Approval Keuangan';
        } elseif ($tujuan == 'Umum') {
            return 'Menunggu Approval Umum';
        } elseif ($tujuan == 'Akademik') {
            return 'Menunggu Approval Akademik';
        } elseif ($tujuan == 'Perpus') {
            return 'Menunggu Approval Perpus';
        } elseif ($tujuan == 'Prodi') {
            return 'Menunggu Approval Prodi';
        } elseif ($tujuan == 'Dekan') {
            return 'Menunggu Approval Dekan';
        } elseif ($tujuan == 'Kabag TU') {
            return 'Menunggu Approval Kabag';
        } elseif ($tujuan == 'Wadek Akademik') {
            return 'Menunggu Approval Wadek Akademik';
        } elseif ($tujuan == 'Wadek Kemahasiswaan') {
            return 'Menunggu Approval Wadek Kemahasiswaan';
        } elseif ($tujuan == 'Wadek Administrasi Umum') {
            return 'Menunggu Approval Wadek Administrasi Umum';
        } elseif ($tujuan == 'PLT') {
            return 'Menunggu Approval PLT';
        } elseif ($tujuan == 'Staff TU') {
            return 'Selesai';
        }
    }
}
