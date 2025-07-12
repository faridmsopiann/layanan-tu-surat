<?php

namespace App\Http\Controllers\ProdiMatematika;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use ZipArchive;

class ProdiMatematikaDisposisiController extends Controller
{
    // Menampilkan semua proposal untuk disposisi oleh Dekan
    public function index()
    {
        $proposals = Proposal::where('status_disposisi', 'Menunggu Approval Prodi Matematika')
            ->orWhere('tujuan_disposisi', 'Prodi Matematika')
            ->paginate(5);
        return view('prodi-matematika.disposisi.index', compact('proposals'));
    }

    public function uploadSK(Request $request, $id)
    {
        $request->validate([
            'soft_file_sk' => 'required|file|mimes:pdf|max:10240',
        ]);

        $proposal = Proposal::findOrFail($id);

        // Simpan file
        $path = $request->file('soft_file_sk')->store('proposals', 'public');

        // Update data proposal
        $proposal->soft_file_sk = $path;
        $proposal->sudah_sk = true;
        $proposal->save();

        return redirect()->back()->with('success', 'Surat Keluar berhasil diupload.');
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
        return view('prodi-matematika.disposisi.disposisi_form', compact('proposal'));
    }

    // Menampilkan form reject untuk proposal
    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('prodi-matematika.disposisi.reject_form', compact('proposal'));
    }

    // Menyimpan disposisi proposal
    public function updateDisposisi(Request $request, Proposal $proposal)
    {
        $request->validate([
            'pesan_disposisi' => 'required|string|max:255',
            'dari' => 'required|string',
        ]);

        $status_disposisi = $this->getStatusDisposisi($request->disposisi);

        $proposal->update([
            'dari' => $request->dari,
            'tujuan_disposisi' => $request->disposisi,
            'pesan_disposisi' => $request->pesan_disposisi,
            'status_disposisi' => $status_disposisi,
        ]);

        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Prodi Matematika')
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

        return redirect()->route('prodi-matematika.disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
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
            ->where('tujuan', 'Prodi Matematika')
            ->first();

        $modal->update([
            'tujuan' => 'Prodi Matematika',  // Update tujuan dari request
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

        return redirect()->route('prodi-matematika.disposisi.index')->with('success', 'Proposal berhasil ditolak.');
    }

    private function getStatusDisposisi($tujuan)
    {
        if ($tujuan == 'Keuangan') {
            return 'Menunggu Approval Keuangan';
        } elseif ($tujuan == 'Umum') {
            return 'Menunggu Approval Umum';
        } elseif ($tujuan == 'Akademik') {
            return 'Menunggu Approval Akademik';
        } elseif ($tujuan == 'Perpus') {
            return 'Menunggu Approval Perpus';
        } elseif ($tujuan == 'Prodi Teknik Informatika') {
            return 'Menunggu Approval Prodi Teknik Informatika';
        } elseif ($tujuan == 'Prodi Teknik Pertambangan') {
            return 'Menunggu Approval Prodi Teknik Pertambangan';
        } elseif ($tujuan == 'Prodi Sistem Informasi') {
            return 'Menunggu Approval Prodi Sistem Informasi';
        } elseif ($tujuan == 'Prodi Matematika') {
            return 'Menunggu Approval Prodi Matematika';
        } elseif ($tujuan == 'Prodi Teknik Kimia') {
            return 'Menunggu Approval Prodi Kimia';
        } elseif ($tujuan == 'Prodi Fisika') {
            return 'Menunggu Approval Prodi Fisika';
        } elseif ($tujuan == 'Prodi Biologi') {
            return 'Menunggu Approval Prodi Biologi';
        } elseif ($tujuan == 'Prodi Agribisnis') {
            return 'Menunggu Approval Prodi Agribisnis';
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
