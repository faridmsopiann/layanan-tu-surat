<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\ModalDisposisi;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PengajuanProposalController extends Controller
{
    // Menampilkan semua proposal untuk TU
    public function index()
    {
        // $proposals = Proposal::all(); // Mengambil semua proposal
        $proposals = Proposal::WhereIn('status_disposisi', ['Memproses', 'Menunggu Approval Kabag', 'Menunggu Approval Dekan', 'Menunggu Approval Keuangan', 'Ditolak'])
            ->paginate(3);
        return view('tu.proposals.indexpengajuan', compact('proposals'));
    }

    // Menampilkan form untuk membuat proposal baru
    public function create()
    {
        return view('tu.proposals.create');
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
            'status_disposisi' => 'required|string|max:255',
        ]);

        $proposal->update([
            'nomor_agenda' => $request->nomor_agenda,
            'tanggal_surat' => $request->tanggal_surat,
            'nomor_surat' => $request->nomor_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'diterima_tanggal' => $request->diterima_tanggal,
            'untuk' => $request->untuk,
            'status_disposisi' => $request->status_disposisi,  // Pastikan ini terupdate
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
            'status_disposisi' => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan, // Pastikan kolom ini ada di tabel proposals
        ]);

        return redirect()->route('tu.proposals.indexpengajuan')->with('success', 'Proposal berhasil ditolak.');
    }

    // Hapus proposal
    public function destroy($id)
    {
        $proposal = Proposal::withTrashed()->find($id);
        $proposal->forceDelete();
        return redirect()->route('tu.proposals.indexpengajuan')->with('success', 'Proposal berhasil dihapus.');
    }
}
