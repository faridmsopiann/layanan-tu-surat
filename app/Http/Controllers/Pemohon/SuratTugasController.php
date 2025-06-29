<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\JenisKegiatan;
use App\Models\Instansi;
use App\Models\Dosen;
use App\Models\ModalDisposisi;
use App\Models\PeranTugas;
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
        $dosenList = Dosen::with('unit')->get();
        $peranList = PeranTugas::all();

        return view('pemohon.surat-tugas.index', compact(
            'suratTugasList',
            'jenisKegiatanList',
            'instansiList',
            'dosenList',
            'peranList'
        ));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'hal' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_kegiatan' => 'required|string',
            'instansi_ids' => 'array',
            'instansi_manual' => 'array',
            'penugasan' => 'array',
            'penugasan.*.unit_asal' => 'required|string|min:2',
            'penugasan.*.peran_tugas_id' => 'required|integer|exists:peran_tugas,id',
            'soft_file.*' => 'nullable|file|max:10240',
            'file_link' => 'nullable|url',
            'asal_surat' => 'required|string',
        ]);

        $softFilePaths = [];
        if ($request->hasFile('soft_file')) {
            foreach ($request->file('soft_file') as $file) {
                $softFilePaths[] = $file->store('proposals', 'public');
            }
        }

        $tahun = now()->year;
        $bulan = now()->month;
        $last = Proposal::withTrashed()->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->latest()->first();
        $increment = $last ? ((int)substr($last->kode_pengajuan, -4)) + 1 : 1;
        $kode = 'P' . $tahun . $bulan . str_pad($increment, 4, '0', STR_PAD_LEFT);
        $nomorAgenda = Proposal::withTrashed()
            ->whereYear('created_at', $tahun)
            ->count() + 1;

        $proposal = Proposal::create([
            'pemohon_id' => Auth::id(),
            'jenis_proposal' => 'Surat Tugas',
            'hal' => $request->hal,
            'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi_kegiatan' => $request->lokasi_kegiatan,
            'kode_pengajuan' => $kode,
            'soft_file' => $softFilePaths ? json_encode($softFilePaths) : null,
            'soft_file_link' => $request->file_link,
            'tanggal_surat' => now(),
            'asal_surat' => $request->asal_surat,
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

        foreach ($request->instansi_ids ?? [] as $id) {
            $proposal->instansi()->create(['instansi_id' => $id]);
        }
        foreach ($request->instansi_manual ?? [] as $manual) {
            if ($manual) $proposal->instansi()->create(['nama_manual' => $manual]);
        }

        foreach ($request->penugasan ?? [] as $p) {
            $proposal->penugasan()->create([
                'dosen_id' => $p['dosen_id'] ?? null,
                'nama_manual' => $p['nama_manual'] ?? null,
                'peran_tugas_id' => $p['peran_tugas_id'],
                'unit_asal' => $p['unit_asal'],
            ]);
        }

        return redirect()->route('pemohon.surat-tugas.index')->with('success', 'Pengajuan Surat Tugas berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
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
                'dosen_id' => $p['dosen_id'] ?? null,
                'nama_manual' => $p['nama_manual'] ?? null,
                'peran_tugas_id' => $p['peran_tugas_id'],
                'unit_asal' => $p['unit_asal'],
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
