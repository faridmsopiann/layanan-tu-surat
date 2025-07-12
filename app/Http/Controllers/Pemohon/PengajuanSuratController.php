<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\KopSurat;
use App\Models\ModalDisposisi;
use App\Models\PengajuanSurat;
use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\TablePosition;
use ZipArchive;

class PengajuanSuratController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with(['pemohon', 'modalDisposisi'])
            ->where('pemohon_id', auth()->id())
            ->where('jenis_proposal', 'Surat Masuk')
            ->whereNull('deleted_at')
            ->paginate(4);

        return view('pemohon.proposals.index', compact('proposals'));
    }

    public function exportPdf($id)
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

    public function exportWord($id)
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

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'required|string',
            'hal' => 'required|string',
            'soft_file.*' => 'nullable|file|max:10240',
            'file_link' => 'nullable|url',
        ]);

        $softFilePaths = [];
        $softFileLink = $request->input('file_link');

        if ($request->hasFile('soft_file')) {
            foreach ($request->file('soft_file') as $file) {
                $path = $file->store('proposals', 'public');
                $softFilePaths[] = $path;
            }
        }

        $tahun = now()->year;
        $bulan = str_pad(now()->month, 2, '0', STR_PAD_LEFT); 

        $lastProposal = Proposal::withTrashed()
            ->whereYear('created_at', $tahun) 
            ->latest()
            ->first();

        $increment = 1;
        if ($lastProposal) {
            $lastKode = substr($lastProposal->kode_pengajuan, -4); 
            $increment = (int)$lastKode + 1;
        }

        $kodePengajuan = 'P' . $tahun . $bulan . str_pad($increment, 4, '0', STR_PAD_LEFT);

        $nomorAgenda = Proposal::withTrashed()
            ->whereYear('created_at', $tahun)
            ->count() + 1;

        $proposal = Proposal::create([
            'pemohon_id' => auth()->id(),
            'tanggal_surat' => $request->tanggal_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'kode_pengajuan' => $kodePengajuan,
            'soft_file' => count($softFilePaths) > 0 ? json_encode($softFilePaths) : null,
            'soft_file_link' => $softFileLink,
            'nomor_agenda' => $nomorAgenda,
            'jenis_proposal' => 'Surat Masuk',
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

        return redirect()->route('pemohon.proposals.index')->with('success', 'Pengajuan surat berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'required|string',
            'hal' => 'required|string',
            'soft_file.*' => 'nullable|file|max:10240',
            'file_link' => 'nullable|url',
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
            'tanggal_surat' => $request->tanggal_surat,
            'asal_surat' => $request->asal_surat,
            'hal' => $request->hal,
            'soft_file_link' => $request->file_link,
        ]);

        return redirect()->back()->with('success', 'Proposal berhasil diperbarui.');
    }


    // Hapus pengajuan surat
    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Cek status proposal
        if (in_array($proposal->status_disposisi, ['Selesai', 'Ditolak'])) {
            // Hapus proposal dengan soft delete
            $proposal->delete();
            return redirect()->route('pemohon.proposals.index')->with('success', 'Pengajuan surat berhasil di-soft delete.');
        } else {
            // Hapus proposal secara permanen
            $proposal->forceDelete();
            return redirect()->route('pemohon.proposals.index')->with('success', 'Pengajuan surat berhasil dihapus secara permanen.');
        }
    }

    // Tampilkan detail surat dalam pop-up
    public function show($id)
    {
        $proposals = Proposal::findOrFail($id);
        // Mengambil modal disposisi yang terkait dengan proposal ini berdasarkan proposal_id
        $modal = ModalDisposisi::where('proposal_id', $id)->with('proposal')->get();

        return view('pemohon.proposals.detail', compact('proposals', 'modal'));
    }
}
