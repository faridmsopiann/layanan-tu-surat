<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\JenisKegiatan;
use App\Models\Instansi;
use App\Models\Jabatan;
use App\Models\KopSurat;
use App\Models\ModalDisposisi;
use App\Models\PegawaiPenugasan;
use App\Models\PejabatPenandatangan;
use App\Models\PeranTugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratTugasController extends Controller
{
    public function index()
    {
        $suratTugasList = Proposal::where('jenis_proposal', 'Surat Tugas')
            ->where('pemohon_id', Auth::id())
            ->with(['instansi', 'penugasan', 'penugasan.peranTugas'])
            ->latest()
            ->paginate(5);

        $jenisKegiatanList = JenisKegiatan::all();
        $instansiList = Instansi::all();
        $pegawaiPenugasanList = PegawaiPenugasan::with('unit')->with('jabatan')->get();
        $peranList = PeranTugas::all();

        return view('pemohon.surat-tugas.index', compact(
            'suratTugasList',
            'jenisKegiatanList',
            'instansiList',
            'pegawaiPenugasanList',
            'peranList'
        ));
    }

    public function exportPdf($id)
    {
        $proposal = Proposal::with([
            'pemohon',
            'jenisKegiatan',
            'instansi',
            'penugasan',
            'penugasan.peranTugas',
            'modalDisposisi'
        ])->findOrFail($id);

        if ($proposal->status_disposisi !== 'Selesai') {
            abort(403, 'Surat belum selesai.');
        }

        $kop = KopSurat::where('nama', 'Kop Surat Tugas')->first();

        $dekanJabatan = Jabatan::where('nama', 'Dekan')->first();
        $penandatangan = $dekanJabatan
            ? PejabatPenandatangan::where('jabatan_id', $dekanJabatan->id)->latest()->first()
            : null;

        $pdf = Pdf::loadView('pdf.surat_tugas_detail', compact('proposal', 'kop', 'penandatangan'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('surat-tugas-' . $proposal->kode_pengajuan . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:kegiatans,id',
            'hal' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_kegiatan' => 'required|string',
            'instansi_ids' => 'array',
            'instansi_manual' => 'array',
            'penugasan' => 'array',
            'penugasan.*.unit_asal' => 'required|string|min:2',
            'penugasan.*.jabatan' => 'required|string|min:2',
            'penugasan.*.peran_tugas_id' => 'required|integer|exists:perans,id',
            'soft_file.*' => 'nullable|file|max:10240',
            'file_link' => 'nullable|url',
            'pertimbangan' => 'required|string',
            'dasar_penugasan' => 'required|string',
            'sumber_biaya' => 'required|string',
        ]);

        $softFilePaths = [];
        if ($request->hasFile('soft_file')) {
            foreach ($request->file('soft_file') as $file) {
                $softFilePaths[] = $file->store('proposals', 'public');
            }
        }

        $tahun = now()->year;
        $bulan = str_pad(now()->month, 2, '0', STR_PAD_LEFT);

        $lastProposal = Proposal::withTrashed()
            ->whereYear('created_at', $tahun)
            ->latest()
            ->first();

        // Increment kode pengajuan
        $increment = 1;
        if ($lastProposal) {
            $lastKode = substr($lastProposal->kode_pengajuan, -4);
            $increment = (int)$lastKode + 1;
        }

        $kodePengajuan = 'P' . $tahun . $bulan . str_pad($increment, 4, '0', STR_PAD_LEFT);
        $nomorAgenda = Proposal::withTrashed()
            ->whereYear('created_at', $tahun)
            ->count() + 1;

        // === Nomor Surat ===
        $incrementNomor = 1;
        if ($lastProposal && $lastProposal->nomor_surat) {
            $parts = explode('/', $lastProposal->nomor_surat);
            if (isset($parts[0])) {
                $lastIncrementPart = str_replace('B-', '', $parts[0]);
                $incrementNomor = (int)$lastIncrementPart + 1;
            }
        }

        $nomorSurat = 'B-' . str_pad($incrementNomor, 3, '0', STR_PAD_LEFT)
            . '/F.9/KP.01.1/' . $bulan . '/' . $tahun;

        $proposal = Proposal::create([
            'pemohon_id' => Auth::id(),
            'jenis_proposal' => 'Surat Tugas',
            'hal' => $request->hal,
            'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi_kegiatan' => $request->lokasi_kegiatan,
            'kode_pengajuan' => $kodePengajuan,
            'soft_file' => $softFilePaths ? json_encode($softFilePaths) : null,
            'soft_file_link' => $request->file_link,
            'tanggal_surat' => now(),
            'asal_surat' => $request->asal_surat,
            'pertimbangan' => $request->pertimbangan,
            'dasar_penugasan' => $request->dasar_penugasan,
            'sumber_biaya' => $request->sumber_biaya,
            'nomor_agenda' => $nomorAgenda,
            'nomor_surat' => $nomorSurat,
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

        foreach ($request->instansi_ids ?? [] as $id) {
            $proposal->instansi()->create(['instansi_id' => $id]);
        }
        foreach ($request->instansi_manual ?? [] as $manual) {
            if ($manual) $proposal->instansi()->create(['nama_manual' => $manual]);
        }

        foreach ($request->penugasan ?? [] as $p) {
            $proposal->penugasan()->create([
                'pegawai_id' => $p['pegawai_id'] ?? null,
                'nama_manual' => $p['nama_manual'] ?? null,
                'peran_tugas_id' => $p['peran_tugas_id'],
                'unit_asal' => $p['unit_asal'],
                'jabatan' => $p['jabatan'],
            ]);
        }

        return redirect()->route('pemohon.surat-tugas.index')->with('success', 'Pengajuan Surat Tugas berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:kegiatans,id',
            'hal' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_kegiatan' => 'required|string',
            'instansi_ids' => 'array',
            'soft_file.*' => 'nullable|file|max:10240',
            'file_link' => 'nullable|url',
            'asal_surat' => 'required|string',
        ]);

        $proposal = Proposal::findOrFail($id);
        if ($request->hasFile('soft_file')) {
            $filePaths = [];
            foreach ($request->file('soft_file') as $file) {
                $filePaths[] = $file->store('proposals', 'public');
            }
            $proposal->soft_file = json_encode($filePaths);
        }

        $proposal->update([
            'hal' => $request->hal,
            'soft_file_link' => $request->file_link,
            'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi_kegiatan' => $request->lokasi_kegiatan,
            'asal_surat' => $request->asal_surat,
            'pertimbangan' => $request->pertimbangan,
            'dasar_penugasan' => $request->dasar_penugasan,
            'sumber_biaya' => $request->sumber_biaya,
        ]);

        $proposal->instansi()->delete();
        $proposal->penugasan()->delete();

        foreach ($request->instansi_ids ?? [] as $id) {
            $proposal->instansi()->create(['instansi_id' => $id]);
        }
        foreach ($request->instansi_manual ?? [] as $manual) {
            if ($manual) $proposal->instansi()->create(['nama_manual' => $manual]);
        }

        foreach ($request->penugasan ?? [] as $p) {
            $proposal->penugasan()->create([
                'pegawai_id' => $p['pegawai_id'] ?? null,
                'nama_manual' => $p['nama_manual'] ?? null,
                'peran_tugas_id' => $p['peran_tugas_id'],
                'unit_asal' => $p['unit_asal'],
                'jabatan' => $p['jabatan'],
            ]);
        }

        return redirect()->route('pemohon.surat-tugas.index')->with('success', 'Pengajuan Surat Tugas berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->delete();

        return redirect()->route('pemohon.surat-tugas.index')->with('success', 'Data berhasil dihapus.');
    }
}
