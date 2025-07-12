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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

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

    public function exportWord($id)
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

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Style default
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // SECTION PERTAMA (halaman utama)
        $section1 = $phpWord->addSection([
            'marginBottom' => 1000,
            'footerHeight' => 300
        ]);

        // KOP SURAT di paling atas
        if ($kop && $kop->kop_surat && file_exists(public_path($kop->kop_surat))) {
            $section1->addImage(public_path($kop->kop_surat), [
                'width' => 460,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
            $section1->addTextBreak(1);
        }

        // Judul
        $section1->addText('SURAT TUGAS', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section1->addText('Nomor: ' . $proposal->nomor_surat, [], ['alignment' => 'center']);
        $section1->addTextBreak(1);

        // Isi surat (justify)
        $section1->addText("Menimbang : {$proposal->pertimbangan}", [], ['alignment' => 'both']);
        $section1->addText("Dasar     : {$proposal->dasar_penugasan}", [], ['alignment' => 'both']);
        $section1->addTextBreak();
        $section1->addText("Memberi Tugas", ['bold' => true], ['alignment' => 'both']);
        $section1->addText("Kepada   : Nama-nama terlampir", [], ['alignment' => 'both']);
        $section1->addText("Untuk    : {$proposal->hal}", [], ['alignment' => 'both']);
        $section1->addText("Sumber Biaya : {$proposal->sumber_biaya}", [], ['alignment' => 'both']);
        $section1->addTextBreak(2);

        // TTD
        $section1->addText('Jakarta, ' . now()->translatedFormat('d F Y'), null, ['alignment' => 'right']);
        $section1->addText('Dekan,', null, ['alignment' => 'right']);

        if ($penandatangan && $penandatangan->ttd && file_exists(public_path($penandatangan->ttd))) {
            $section1->addImage(public_path($penandatangan->ttd), [
                'width' => 120,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
            ]);
        } else {
            $section1->addTextBreak(3);
        }

        $section1->addText($penandatangan->nama ?? 'Husni Teja Sukmana, S.T., M.Sc., Ph.D', ['bold' => true], ['alignment' => 'right']);
        $section1->addText('NIP. ' . ($penandatangan->nip ?? '197710302001121003'), null, ['alignment' => 'right']);

        // FOOTER hanya di section pertama
        $footer = $section1->addFooter();
        $footerImagePath = public_path('images/footer.png');
        if (file_exists($footerImagePath)) {
            $footer->addImage($footerImagePath, [
                'width' => 450,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
        }

        // SECTION KEDUA (lampiran)
        $section2 = $phpWord->addSection();

        $section2->addText("Lampiran Surat Nomor: {$proposal->nomor_surat}", [], ['alignment' => 'both']);
        $section2->addText('Tanggal: ' . now()->translatedFormat('d F Y'), [], ['alignment' => 'both']);

        $instansiTerkait = $proposal->instansi->pluck('instansi.nama')->filter()->implode(', ');
        $instansiManual = $proposal->instansi->pluck('nama_manual')->filter()->implode(', ');
        $instansiGabung = trim($instansiTerkait . ($instansiTerkait && $instansiManual ? ', ' : '') . $instansiManual);

        $section2->addText(
            "DAFTAR NAMA DOSEN PADA " . strtoupper($proposal->hal) .
            " PADA TANGGAL " . \Carbon\Carbon::parse($proposal->tanggal_mulai)->translatedFormat('d') .
            " s/d " . \Carbon\Carbon::parse($proposal->tanggal_selesai)->translatedFormat('d F Y') .
            " DI {$instansiGabung}.",
            [],
            ['alignment' => 'both']
        );

        // Tabel lampiran
        $table = $section2->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50
        ]);
        $table->addRow();
        $table->addCell(500)->addText('No', ['bold' => true]);
        $table->addCell(3000)->addText('Nama', ['bold' => true]);
        $table->addCell(3000)->addText('Jabatan', ['bold' => true]);

        foreach ($proposal->penugasan as $i => $p) {
            $table->addRow();
            $table->addCell(500)->addText($i + 1);
            $table->addCell(3000)->addText($p->pegawaiPenugasan->nama ?? $p->nama_manual);
            $table->addCell(3000)->addText($p->jabatan ?? '-');
        }

        $section2->addTextBreak(2);
        $section2->addText('Dekan,', null, ['alignment' => 'right']);

        if ($penandatangan && $penandatangan->ttd && file_exists(public_path($penandatangan->ttd))) {
            $section2->addImage(public_path($penandatangan->ttd), [
                'width' => 120,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
            ]);
        } else {
            $section2->addTextBreak(3);
        }

        $section2->addText($penandatangan->nama ?? 'Husni Teja Sukmana, S.T., M.Sc., Ph.D', ['bold' => true], ['alignment' => 'right']);
        $section2->addText('NIP. ' . ($penandatangan->nip ?? '197710302001121003'), null, ['alignment' => 'right']);

        // Simpan dan download
        $filename = 'surat-tugas-' . $proposal->kode_pengajuan . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
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
            ->where('jenis_proposal', 'Surat Tugas')
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
            ->where('jenis_proposal', 'Surat Tugas')
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
