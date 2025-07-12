<?php

namespace App\Http\Controllers\Plt;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\KopSurat;
use App\Models\PejabatPenandatangan;
use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\TablePosition;

class PltExportController extends Controller
{
    public function exportPdfSuratMasuk($id)
    {
        $proposal = Proposal::with(['pemohon', 'modalDisposisi'])
            ->findOrFail($id);

        if ($proposal->status_disposisi !== 'Selesai') {
            abort(403, 'Surat belum selesai.');
        }

        $kop = KopSurat::where('nama', 'Kop Surat Masuk')->first();

        // Surat Masuk ➜ HANYA data utama + modalDisposisi
        $pdf = Pdf::loadView('pdf.proposal_detail', compact('proposal', 'kop'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('surat-masuk-' . $proposal->kode_pengajuan . '.pdf');
    }

    public function exportPdfSuratTugas($id)
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

    public function exportWordSuratMasuk($id)
    {
        $proposal = Proposal::with(['pemohon', 'modalDisposisi'])->findOrFail($id);

        if ($proposal->status_disposisi !== 'Selesai') {
            abort(403, 'Surat belum selesai.');
        }

        $kop = KopSurat::where('nama', 'Kop Surat Masuk')->first();
        $phpWord = new PhpWord();

        $section = $phpWord->addSection([
            'marginTop' => 1000,
            'marginBottom' => 800,
            'footerHeight' => 300,
        ]);

        // Kop surat
        if ($kop && $kop->kop_surat && file_exists(public_path($kop->kop_surat))) {
            $section->addImage(public_path($kop->kop_surat), [
                'width' => 460,
                'alignment' => Jc::CENTER,
            ]);
            $section->addTextBreak(1);
        }

        // Judul
        $section->addText('DETAIL SURAT MASUK', [
            'bold' => true,
            'size' => 16,
            'allCaps' => true,
            'underline' => 'single',
        ], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Informasi Surat - TABEL
        $infoTableStyle = [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => Jc::CENTER,
        ];
        $phpWord->addTableStyle('infoTable', $infoTableStyle);
        $infoTable = $section->addTable('infoTable');

        $data = [
            'Kode Pengajuan'    => $proposal->kode_pengajuan,
            'Nomor Agenda'      => $proposal->nomor_agenda,
            'Tanggal Surat'     => \Carbon\Carbon::parse($proposal->tanggal_surat)->translatedFormat('d F Y'),
            'Asal Surat'        => $proposal->asal_surat,
            'Hal'               => $proposal->hal,
            'Status'            => $proposal->status_disposisi,
            'Nama Pemohon'      => $proposal->pemohon->name,
            'Diterima Tanggal'  => $proposal->diterima_tanggal
                ? \Carbon\Carbon::parse($proposal->diterima_tanggal)->translatedFormat('d F Y') : '-',
        ];

        foreach ($data as $label => $value) {
            $infoTable->addRow();
            $infoTable->addCell(3000, ['bgColor' => 'D9EAF7'])->addText($label, ['bold' => true]);
            if ($label === 'Status') {
                $infoTable->addCell(7000, ['bgColor' => 'DFF6DD'])->addText((string) $value);
            } else {
                $infoTable->addCell(7000)->addText((string) $value);
            }
        }

        $section->addTextBreak(1);

        // Riwayat Disposisi
        $section->addText('Riwayat Disposisi:', ['bold' => true]);

        $disposisiTableStyle = [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => Jc::CENTER,
            'position' => TablePosition::XALIGN_CENTER,
        ];
        $phpWord->addTableStyle('disposisiTable', $disposisiTableStyle);
        $table = $section->addTable('disposisiTable');

        // Header
        $headerStyle = ['bold' => true, 'size' => 11];
        $headerCellStyle = ['bgColor' => 'D9EAF7'];
        $table->addRow();
        $table->addCell(500, $headerCellStyle)->addText('No', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Tujuan', $headerStyle);
        $table->addCell(1200, $headerCellStyle)->addText('Status', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Tgl Diterima', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Tgl Proses', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('Diverifikasi Oleh', $headerStyle);
        $table->addCell(2500, $headerCellStyle)->addText('Keterangan', $headerStyle);

        foreach ($proposal->modalDisposisi as $i => $dis) {
            $table->addRow();
            $table->addCell()->addText((string) ($i + 1));
            $table->addCell()->addText($dis->tujuan ?? '-');
            $table->addCell(null, ['bgColor' => 'DFF6DD'])->addText((string) $dis->status);
            $table->addCell()->addText($dis->tanggal_diterima
                ? \Carbon\Carbon::parse($dis->tanggal_diterima)->translatedFormat('d F Y') : '-');
            $table->addCell()->addText($dis->tanggal_proses
                ? \Carbon\Carbon::parse($dis->tanggal_proses)->translatedFormat('d F Y') : '-');
            $table->addCell()->addText($dis->diverifikasi_oleh ?? '-');
            $table->addCell()->addText($dis->keterangan ?? '-');
        }

        $section->addTextBreak(1);

        // Footer
        $footer = $section->addFooter();
        $footer->addText('Dicetak pada: ' . now()->format('d-m-Y H:i'), [
            'size' => 10,
            'italic' => true,
            'color' => '777777',
        ], ['alignment' => Jc::RIGHT]);

        // Export
        $filename = 'surat-masuk-' . $proposal->kode_pengajuan . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportWordSuratTugas($id)
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
}