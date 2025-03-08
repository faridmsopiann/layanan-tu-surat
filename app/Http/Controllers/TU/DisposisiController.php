<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    // Menampilkan semua proposal untuk disposisi
    public function index()
    {
        // Mengambil proposal dengan status 'Memproses' dan 'Menunggu Approval Keuangan'
        $proposals = Proposal::withTrashed()->where('tujuan_disposisi', 'Kabag Tata Usaha')
            ->orWhereIn('status_disposisi', ['Memproses', 'Menunggu Approval Kabag',])
            ->whereNotNull('nomor_agenda')
            ->whereNotNull('untuk')
            ->whereNotNull('diterima_tanggal')
            ->paginate(3);

        // Mengirim data proposals ke view
        return view('tu.disposisi.index', compact('proposals'));
    }

    // Menampilkan halaman form disposisi untuk proposal
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id); // Mendapatkan proposal berdasarkan ID
        return view('tu.disposisi.disposisi_form', compact('proposal')); // Mengarah ke view form disposisi
    }

    // Menyimpan disposisi proposal
    public function update(Request $request, Proposal $proposal)
    {
        // Validasi input dari form
        $request->validate([
            'disposisi' => 'required|string',
            'pesan_disposisi' => 'required|string|max:255',
            'dari' => 'required|string',
        ]);

        // Tentukan status disposisi berdasarkan tujuan disposisi
        $status_disposisi = $this->getStatusDisposisi($request->disposisi);

        // Mengupdate tujuan_disposisi, pesan_disposisi, dan status_disposisi di proposal
        $proposal->update([
            'dari' => $request->dari,
            'tujuan_disposisi' => $request->disposisi,  // Menyimpan tujuan disposisi yang dipilih
            'pesan_disposisi' => $request->pesan_disposisi,  // Menyimpan pesan disposisi
            'status_disposisi' => $status_disposisi,  // Menyimpan status disposisi berdasarkan tujuan
        ]);

        // Ambil modal_disposisi yang terkait dengan proposal
        $modal_staff_tu = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Staff TU')
            ->where('status', 'Diproses')
            ->first();

        $modal_kabag_tu = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Kabag TU')
            ->where('status', 'Diproses')
            ->first();

        $tuUser = '';

        if ($modal_staff_tu) {
            $tuUser = 'Susi Sundari. SE';
            $modal_staff_tu->update([
                'tujuan' => 'Staff TU',
                'status' => 'Disetujui',
                'tanggal_diterima' => $proposal->diterima_tanggal,
                'tanggal_proses' => now()->format('Y-m-d H:i:s'),
                'diverifikasi_oleh' => $tuUser,
                'keterangan' => $request->pesan_disposisi,
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
        } elseif ($modal_kabag_tu) {
            $tuUser = 'Dra. Hj. Faojah, MA';
            $modal_kabag_tu->update([
                'tujuan' => 'Kabag TU',
                'status' => 'Disetujui',
                'tanggal_diterima' => $proposal->diterima_tanggal,
                'tanggal_proses' => now()->format('Y-m-d H:i:s'),
                'diverifikasi_oleh' => $tuUser,
                'keterangan' => $request->pesan_disposisi,
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
        }

        // optional($modal_kabag_tu)->update([
        //     'tujuan' => 'Kabag TU',
        //     'status' => 'Disetujui',
        //     'tanggal_diterima' => $proposal->diterima_tanggal,
        //     'tanggal_proses' => now()->format('Y-m-d H:i:s'),
        //     'diverifikasi_oleh' => auth()->user()->name,
        //     'keterangan' => $request->pesan_disposisi,
        // ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('tu.disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
    }

    // Menampilkan form reject
    public function rejectForm($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('tu.disposisi.reject_form', compact('proposal'));
    }

    // Menyimpan penolakan proposal
    public function rejectSubmit(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tujuan' => 'required|string',
            'pesan' => 'required|string',
        ]);

        // Mengambil proposal berdasarkan ID
        $proposal = Proposal::findOrFail($id);

        // Update status disposisi dan menyimpan data dari form
        $proposal->status_disposisi = 'Ditolak';
        $proposal->dari = 'Kabag TU';  // Menyimpan nilai 'Dari' di kolom 'dari'
        $proposal->tujuan_disposisi = 'Staff TU';  // Menyimpan nilai tujuan
        $proposal->pesan_disposisi = $request->pesan;  // Menyimpan pesan
        $proposal->save();

        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Kabag TU')
            ->first();

        $modal->update([
            'tujuan' => 'Kabag TU',  // Update tujuan dari request
            'status' => 'Ditolak',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now()->format('Y-m-d H:i:s'),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => auth()->user()->name,  // Nama user yang sedang login
            'keterangan' => $request->pesan,  // Pesan dari request
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => 'Staff TU',
            'status' => 'Ditolak',
            'tanggal_diterima' => now()->format('Y-m-d H:i:s'),
            'tanggal_proses' => now()->format('Y-m-d H:i:s'),
            'diverifikasi_oleh' => 'Susi Sundari. SE',
            'keterangan' => 'Selesai',
        ]);

        // Redirect ke halaman disposisi dengan pesan sukses
        return redirect()->route('tu.disposisi.index')->with('success', 'Proposal berhasil ditolak');
    }

    // Menyelesaikan proposal
    public function selesaikan($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Lakukan tindakan yang diperlukan, seperti menghapus proposal
        $proposal->delete();

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('tu.disposisi.index')->with('success', 'Proposal telah selesai.');
    }

    // Menentukan status disposisi berdasarkan tujuan disposisi
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
