<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;
use ZipArchive;

class PengajuanProposalController extends Controller
{
    // Menampilkan semua proposal untuk TU
    public function index()
    {
        // $proposals = Proposal::all(); // Mengambil semua proposal
        $proposals = Proposal::withTrashed()
            ->WhereIn('status_disposisi', ['Memproses', 'Menunggu Approval Kabag', 'Menunggu Approval Dekan', 'Menunggu Approval Wadek Akademik', 'Menunggu Approval Wadek Kemahasiswaan', 'Menunggu Approval Wadek Administrasi Umum', 'Menunggu Approval Prodi Teknik Informatika', 'Menunggu Approval Prodi Agribisnis', 'Menunggu Approval Prodi Sistem Informasi', 'Menunggu Approval Prodi Matematika', 'Menunggu Approval Prodi Fisika', 'Menunggu Approval Prodi Kimia', 'Menunggu Approval Prodi Biologi', 'Menunggu Approval Prodi Teknik Pertambangan', 'Menunggu Approval PLT',  'Menunggu Approval Akademik', 'Menunggu Approval Umum', 'Menunggu Approval Perpus', 'Ditolak', 'Selesai'])
            ->paginate(3);
        return view('tu.proposals.indexpengajuan', compact('proposals'));
    }

    // Menampilkan form untuk membuat proposal baru
    public function create()
    {
        return view('tu.proposals.create');
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

    // Menyimpan proposal baru ke database
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nomor_agenda' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'required|string|max:255',
            'asal_surat' => 'required|string|max:255',
            'hal' => 'required|string|max:255',
            'diterima_tanggal' => 'required|date',
            'untuk' => 'required|string|max:255',
            'status_disposisi' => 'required|string|max:255',
        ]);

        Proposal::create($request->all());
        return redirect()->route('tu.proposals.indexpengajuan')->with('success', 'Proposal berhasil ditambahkan.');
    }

    // Menampilkan form edit proposal
    public function edit(Proposal $proposal)
    {
        return view('tu.proposals.edit', compact('proposal'));
    }

    // Update proposal
    public function update(Request $request, Proposal $proposal)
    {
        $request->validate([
            'nomor_agenda' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'required|string|max:255',
            'asal_surat' => 'required|string|max:255',
            'hal' => 'required|string|max:255',
            'diterima_tanggal' => 'required|date',
            'untuk' => 'required|string|max:255',
            'jenis_surat' => 'required|string|max:255',
        ]);

        $proposal->update([
            'nomor_agenda' => $request->nomor_agenda,
            'tanggal_surat' => $request->tanggal_surat,
            'nomor_surat' => $request->nomor_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'diterima_tanggal' => $request->diterima_tanggal,
            'untuk' => $request->untuk,
            'jenis_proposal' => $request->jenis_surat,
            'perlu_sk' => $request->input('perlu_sk', 0),
            'pihak_pembuat_sk' => $request->input('pihak_pembuat_sk'),
            'pihak_ttd' => $request->has('pihak_ttd') ? json_encode($request->pihak_ttd) : null,
        ]);

        // Ambil modal_disposisi yang terkait dengan proposal
        $modalDisposisi = ModalDisposisi::where('proposal_id', $proposal->id)->first();

        // Pastikan modal_disposisi ditemukan sebelum mengupdate
        if ($modalDisposisi) {
            $modalDisposisi->update([
                'tanggal_diterima' => $request->diterima_tanggal,
            ]);
        }


        return redirect()->route('tu.proposals.indexpengajuan')->with('success', 'Proposal berhasil diupdate.');
    }

    // // Menampilkan form alasan penolakan
    // public function rejectForm(Proposal $proposal)
    // {
    //     return view('tu.proposals.reject', compact('proposal'));
    // }

    // Menyimpan alasan penolakan dan mengubah status proposal
    public function reject(Request $request, Proposal $proposal)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        // Update status proposal menjadi 'Ditolak' dan simpan alasan penolakan
        $proposal->update([
            'alasan_penolakan' => $request->alasan_penolakan, // Pastikan kolom ini ada di tabel proposals
        ]);

        return redirect()->route('tu.proposals.indexpengajuan')->with('success', 'Feedback proposal berhasil dikirim.');
    }

    // Hapus proposal
    public function destroy($id)
    {
        $proposal = Proposal::withTrashed()->find($id);
        $proposal->forceDelete();
        return redirect()->route('tu.proposals.indexpengajuan')->with('success', 'Proposal berhasil dihapus.');
    }
}
