<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index()
    {
        $proposals = Proposal::withTrashed()->where('tujuan_disposisi', 'Kabag Tata Usaha')
            ->orWhereIn('status_disposisi', ['Memproses', 'Menunggu Approval Kabag',])
            ->whereNotNull('nomor_agenda')
            ->whereNotNull('untuk')
            ->whereNotNull('diterima_tanggal')
            ->paginate(3);

        return view('tu.disposisi.index', compact('proposals'));
    }

    public function uploadSK(Request $request, $id)
    {
        $request->validate([
            'soft_file_sk' => 'required|file|mimes:pdf|max:10240',
        ]);

        $proposal = Proposal::findOrFail($id);

        $path = $request->file('soft_file_sk')->store('proposals', 'public');

        $proposal->soft_file_sk = $path;
        $proposal->sudah_sk = true;
        $proposal->save();

        return redirect()->back()->with('success', 'Surat Keluar berhasil diupload.');
    }

    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id); 
        return view('tu.disposisi.disposisi_form', compact('proposal')); 
    }

    public function update(Request $request, Proposal $proposal)
    {
        $request->validate([
            'disposisi' => 'required|string',
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
            $modal_kabag_tu->update([
                'tujuan' => 'Kabag TU',
                'status' => 'Disetujui',
                'tanggal_diterima' => $proposal->diterima_tanggal,
                'tanggal_proses' => now()->format('Y-m-d H:i:s'),
                'diverifikasi_oleh' => auth()->user()->name,
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

        return redirect()->route('tu.disposisi.index')->with('success', 'Proposal berhasil didisposisi.');
    }

    public function rejectForm($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('tu.disposisi.reject_form', compact('proposal'));
    }

    public function rejectSubmit(Request $request, $id)
    {
        $request->validate([
            'tujuan' => 'required|string',
            'pesan' => 'required|string',
        ]);

        $proposal = Proposal::findOrFail($id);

        $proposal->status_disposisi = 'Ditolak';
        $proposal->dari = 'Kabag TU';  
        $proposal->tujuan_disposisi = 'Staff TU';  
        $proposal->pesan_disposisi = $request->pesan;
        $proposal->save();

        $modal = ModalDisposisi::where('proposal_id', $proposal->id)
            ->where('tujuan', 'Kabag TU')
            ->first();

        $modal->update([
            'tujuan' => 'Kabag TU', 
            'status' => 'Ditolak',
            'tanggal_diterima' => $modal->tanggal_diterima,  
            'tanggal_proses' => now()->format('Y-m-d H:i:s'),  
            'diverifikasi_oleh' => auth()->user()->name,
            'keterangan' => $request->pesan, 
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

        return redirect()->route('tu.disposisi.index')->with('success', 'Proposal berhasil ditolak');
    }

    public function selesaikan($id)
    {
        $proposal = Proposal::findOrFail($id);

        $proposal->delete();

        return redirect()->route('tu.disposisi.index')->with('success', 'Proposal telah selesai.');
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
