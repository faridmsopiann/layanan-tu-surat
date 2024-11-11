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
            ->where('tujuan', 'Kabag Tata Usaha')
            ->first();

        if ($modal_staff_tu) {
            $modal_staff_tu->update([
                'tujuan' => 'Staff TU',
                'status' => 'Disetujui',
                'tanggal_diterima' => $proposal->diterima_tanggal,
                'tanggal_proses' => now()->format('Y-m-d'),
                'diverifikasi_oleh' => auth()->user()->name,
                'keterangan' => $request->pesan_disposisi,
            ]);

            ModalDisposisi::create([
                'proposal_id' => $proposal->id,
                'tujuan' => $request->disposisi,
                'status' => 'Diproses',
                'tanggal_diterima' => now()->format('Y-m-d'),
                'tanggal_proses' => null,
                'diverifikasi_oleh' => null,
                'keterangan' => null,
            ]);
        } elseif ($modal_kabag_tu) {

            if ($request->disposisi == 'Keuangan') {
                ModalDisposisi::create([
                    'proposal_id' => $proposal->id,
                    'tujuan' => $request->disposisi,
                    'status' => 'Diproses',
                    'tanggal_diterima' => now()->format('Y-m-d'),
                    'tanggal_proses' => null,
                    'diverifikasi_oleh' => null,
                    'keterangan' => null,
                ]);
            } else {
                ModalDisposisi::create([
                    'proposal_id' => $proposal->id,
                    'tujuan' => 'Staff TU',
                    'status' => 'Disetujui',
                    'tanggal_diterima' => now()->format('Y-m-d'),
                    'tanggal_proses' => now()->format('Y-m-d'),
                    'diverifikasi_oleh' => 'Bu Susi',
                    'keterangan' => 'Selesai',
                ]);
            }
        }

        optional($modal_kabag_tu)->update([
            'tujuan' => 'Kabag Tata Usaha',
            'status' => 'Disetujui',
            'tanggal_diterima' => $proposal->diterima_tanggal,
            'tanggal_proses' => now()->format('Y-m-d'),
            'diverifikasi_oleh' => auth()->user()->name,
            'keterangan' => $request->pesan_disposisi,
        ]);

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
        $proposal->dari = 'Kabag Tata Usaha';  // Menyimpan nilai 'Dari' di kolom 'dari'
        $proposal->tujuan_disposisi = 'Staff TU';  // Menyimpan nilai tujuan
        $proposal->pesan_disposisi = $request->pesan;  // Menyimpan pesan
        $proposal->save();

        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Kabag Tata Usaha')
            ->first();

        $modal->update([
            'tujuan' => 'Kabag Tata Usaha',  // Update tujuan dari request
            'status' => 'Ditolak',
            'tanggal_diterima' => $modal->tanggal_diterima,  // Tetap gunakan tanggal_diterima yang sudah ada
            'tanggal_proses' => now()->format('Y-m-d'),  // Update dengan tanggal saat ini
            'diverifikasi_oleh' => auth()->user()->name,  // Nama user yang sedang login
            'keterangan' => $request->pesan,  // Pesan dari request
        ]);

        ModalDisposisi::create([
            'proposal_id' => $proposal->id,
            'tujuan' => 'Staff TU',
            'status' => 'Ditolak',
            'tanggal_diterima' => now()->format('Y-m-d'),
            'tanggal_proses' => now()->format('Y-m-d'),
            'diverifikasi_oleh' => 'Bu Susi',
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
        } elseif ($tujuan == 'Staff TU') {
            return 'Selesai';
        } else {
            return 'Menunggu Approval Dekan';
        }
    }
}
