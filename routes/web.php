<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\JenisKegiatanController;
use App\Http\Controllers\Admin\KopSuratController;
use App\Http\Controllers\Admin\PegawaiPenugasanController;
use App\Http\Controllers\Admin\PejabatPenandatanganController;
use App\Http\Controllers\Admin\PeranTugasController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UnitKerjaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Akademik\AkademikController;
use App\Http\Controllers\Akademik\AkademikDisposisiController;
use App\Http\Controllers\Akademik\AkademikExportController;
use App\Http\Controllers\Akademik\AkademikExportPdfController;
use App\Http\Controllers\Akademik\ArsipSuratAkademikController;
use App\Http\Controllers\Akademik\MonitoringAkademikController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Dekan\ArsipSuratDekanController;
use App\Http\Controllers\Dekan\DekanController;
use App\Http\Controllers\Dekan\DekanExportController;
use App\Http\Controllers\Dekan\DekanExportPdfController;
use App\Http\Controllers\Dekan\DisposisiController as DekanDisposisiController;
use App\Http\Controllers\Dekan\MonitoringController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Keuangan\ArsipSuratKeuanganController;
use App\Http\Controllers\Keuangan\KeuanganController;
use App\Http\Controllers\Keuangan\KeuanganDisposisiController;
use App\Http\Controllers\Keuangan\KeuanganExportController;
use App\Http\Controllers\Keuangan\KeuanganExportPdfController;
use App\Http\Controllers\Keuangan\MonitoringKeuanganController;
use App\Http\Controllers\Pemohon\PemohonController;
use App\Http\Controllers\Pemohon\PengajuanSuratController;
use App\Http\Controllers\Pemohon\SuratTugasController;
use App\Http\Controllers\Perpus\ArsipSuratPerpusController;
use App\Http\Controllers\Perpus\MonitoringPerpusController;
use App\Http\Controllers\Perpus\PerpusController;
use App\Http\Controllers\Perpus\PerpusDisposisiController;
use App\Http\Controllers\Perpus\PerpusExportController;
use App\Http\Controllers\Perpus\PerpusExportPdfController;
use App\Http\Controllers\Plt\ArsipSuratPltController;
use App\Http\Controllers\Plt\MonitoringPltController;
use App\Http\Controllers\Plt\PltController;
use App\Http\Controllers\Plt\PltDisposisiController;
use App\Http\Controllers\Plt\PltExportController;
use App\Http\Controllers\Plt\PltExportPdfController;
use App\Http\Controllers\ProdiAgribisnis\ArsipSuratProdiAgribisnisController;
use App\Http\Controllers\ProdiAgribisnis\MonitoringProdiAgribisnisController;
use App\Http\Controllers\ProdiAgribisnis\ProdiAgribisnisController;
use App\Http\Controllers\ProdiAgribisnis\ProdiAgribisnisDisposisiController;
use App\Http\Controllers\ProdiAgribisnis\ProdiAgribisnisExportController;
use App\Http\Controllers\ProdiAgribisnis\ProdiAgribisnisExportPdfController;
use App\Http\Controllers\ProdiBiologi\ArsipSuratProdiBiologiController;
use App\Http\Controllers\ProdiBiologi\MonitoringProdiBiologiController;
use App\Http\Controllers\ProdiBiologi\ProdiBiologiController;
use App\Http\Controllers\ProdiBiologi\ProdiBiologiDisposisiController;
use App\Http\Controllers\ProdiBiologi\ProdiBiologiExportController;
use App\Http\Controllers\ProdiBiologi\ProdiBiologiExportPdfController;
use App\Http\Controllers\ProdiFisika\ArsipSuratProdiFisikaController;
use App\Http\Controllers\ProdiFisika\MonitoringProdiFisikaController;
use App\Http\Controllers\ProdiFisika\ProdiFisikaController;
use App\Http\Controllers\ProdiFisika\ProdiFisikaDisposisiController;
use App\Http\Controllers\ProdiFisika\ProdiFisikaExportController;
use App\Http\Controllers\ProdiFisika\ProdiFisikaExportPdfController;
use App\Http\Controllers\ProdiKimia\ArsipSuratProdiKimiaController;
use App\Http\Controllers\ProdiKimia\MonitoringProdiKimiaController;
use App\Http\Controllers\ProdiKimia\ProdiKimiaController;
use App\Http\Controllers\ProdiKimia\ProdiKimiaDisposisiController;
use App\Http\Controllers\ProdiKimia\ProdiKimiaExportController;
use App\Http\Controllers\ProdiKimia\ProdiKimiaExportPdfController;
use App\Http\Controllers\ProdiMatematika\ArsipSuratProdiMatematikaController;
use App\Http\Controllers\ProdiMatematika\MonitoringProdiMatematikaController;
use App\Http\Controllers\ProdiMatematika\ProdiMatematikaController;
use App\Http\Controllers\ProdiMatematika\ProdiMatematikaDisposisiController;
use App\Http\Controllers\ProdiMatematika\ProdiMatematikaExportController;
use App\Http\Controllers\ProdiMatematika\ProdiMatematikaExportPdfController;
use App\Http\Controllers\ProdiSI\ArsipSuratProdiSIController;
use App\Http\Controllers\ProdiSI\MonitoringProdiSIController;
use App\Http\Controllers\ProdiSI\ProdiSIController;
use App\Http\Controllers\ProdiSI\ProdiSIDisposisiController;
use App\Http\Controllers\ProdiSI\ProdiSIExportController;
use App\Http\Controllers\ProdiSI\ProdiSIExportPdfController;
use App\Http\Controllers\ProdiTI\ArsipSuratProdiTIController;
use App\Http\Controllers\ProdiTI\MonitoringProdiTIController;
use App\Http\Controllers\ProdiTI\ProdiTIController;
use App\Http\Controllers\ProdiTI\ProdiTIDisposisiController;
use App\Http\Controllers\ProdiTI\ProdiTIExportController;
use App\Http\Controllers\ProdiTI\ProdiTIExportPdfController;
use App\Http\Controllers\ProdiTP\ArsipSuratProdiTPController;
use App\Http\Controllers\ProdiTP\MonitoringProdiTPController;
use App\Http\Controllers\ProdiTP\ProdiTPController;
use App\Http\Controllers\ProdiTP\ProdiTPDisposisiController;
use App\Http\Controllers\ProdiTP\ProdiTPExportController;
use App\Http\Controllers\ProdiTP\ProdiTPExportPdfController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TU\ArsipSuratController;
use App\Http\Controllers\TU\DisposisiController;
use App\Http\Controllers\TU\MonitoringTUController;
use App\Http\Controllers\TU\PengajuanProposalController;
use App\Http\Controllers\TU\ProposalMasukController;
use App\Http\Controllers\TU\TUController;
use App\Http\Controllers\TU\TuExportController;
use App\Http\Controllers\Umum\ArsipSuratUmumController;
use App\Http\Controllers\Umum\MonitoringUmumController;
use App\Http\Controllers\Umum\UmumController;
use App\Http\Controllers\Umum\UmumDisposisiController;
use App\Http\Controllers\Umum\UmumExportController;
use App\Http\Controllers\Umum\UmumExportPdfController;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest']);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Tampilkan halaman QR berisi gambar QR Code
Route::get('login-qr', [QRCodeController::class, 'generateLoginQR'])->name('qr.login');
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.surat')->middleware(['auth']);



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Pemohon')) {
            return redirect()->route('pemohon.dashboard');
        } elseif ($user->hasRole('Tata Usaha')) {
            return redirect()->route('tu.dashboard');
        } elseif ($user->hasRole('Dekanat')) {
            return redirect()->route('dekanat.dashboard');
        } elseif ($user->hasRole('Keuangan')) {
            return redirect()->route('keuangan.dashboard');
        } elseif ($user->hasRole('Prodi Teknik Informatika')) {
            return redirect()->route('prodi-teknik-informatika.dashboard');
        } elseif ($user->hasRole('Prodi Agribisnis')) {
            return redirect()->route('prodi-agribisnis.dashboard');
        } elseif ($user->hasRole('Prodi Sistem Informasi')) {
            return redirect()->route('prodi-sistem-informasi.dashboard');
        } elseif ($user->hasRole('Prodi Matematika')) {
            return redirect()->route('prodi-matematika.dashboard');
        } elseif ($user->hasRole('Prodi Fisika')) {
            return redirect()->route('prodi-fisika.dashboard');
        } elseif ($user->hasRole('Prodi Kimia')) {
            return redirect()->route('prodi-kimia.dashboard');
        } elseif ($user->hasRole('Prodi Biologi')) {
            return redirect()->route('prodi-biologi.dashboard');
        } elseif ($user->hasRole('Prodi Teknik Pertambangan')) {
            return redirect()->route('prodi-teknik-pertambangan.dashboard');
        } elseif ($user->hasRole('Akademik')) {
            return redirect()->route('akademik.dashboard');
        } elseif ($user->hasRole('Umum')) {
            return redirect()->route('umum.dashboard');
        } elseif ($user->hasRole('Perpus')) {
            return redirect()->route('perpus.dashboard');
        } elseif ($user->hasRole('PLT')) {
            return redirect()->route('plt.dashboard');
        } else {
            abort(403, 'Unauthorized action.');
        }
    })->name('dashboard');

    Route::middleware(['auth', 'role:Admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Routes untuk fitur manajemen user
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Routes untuk fitur manajemen proposal
        Route::get('/admin/proposals', [ProposalController::class, 'index'])->name('admin.proposals.index');
        Route::get('/admin/proposals/create', [ProposalController::class, 'create'])->name('admin.proposals.create');
        Route::post('/admin/proposals', [ProposalController::class, 'store'])->name('admin.proposals.store');
        Route::get('/admin/proposals/{proposal}/edit', [ProposalController::class, 'edit'])->name('admin.proposals.edit');
        Route::put('/admin/proposals/{proposal}', [ProposalController::class, 'update'])->name('admin.proposals.update');
        Route::delete('/admin/proposals/{proposal}', [ProposalController::class, 'destroy'])->name('admin.proposals.destroy');

        // Manajemen Role
        Route::resource('roles', RoleController::class);

        // Master Surat Tugas
        Route::resource('admin/jenis-kegiatan', JenisKegiatanController::class)->names('admin.jenis-kegiatan');
        Route::resource('admin/jabatan', JabatanController::class)->names('admin.jabatan');
        Route::resource('admin/instansi', InstansiController::class)->names('admin.instansi');
        Route::resource('admin/peran-tugas', PeranTugasController::class)->names('admin.peran-tugas');
        Route::resource('admin/unit-kerja', UnitKerjaController::class)->names('admin.unit-kerja');
        Route::resource('admin/pegawai-penugasan', PegawaiPenugasanController::class)->names('admin.pegawai-penugasan');

        // Master Kop Surat
        Route::resource('admin/kop-surat', KopSuratController::class)->names('admin.kop-surat');
        Route::resource('admin/pejabat-penandatangan', PejabatPenandatanganController::class)->names('admin.pejabat-penandatangan');
    });

    Route::middleware(['auth', 'role:Pemohon'])->group(function () {
        Route::get('/pemohon/dashboard', [PemohonController::class, 'dashboard'])->name('pemohon.dashboard');

        Route::get('/pemohon/proposals', [PengajuanSuratController::class, 'index'])->name('pemohon.proposals.index');
        Route::get('/pemohon/proposals/create', [PengajuanSuratController::class, 'create'])->name('pemohon.proposals.create');
        Route::post('/pemohon/proposals', [PengajuanSuratController::class, 'store'])->name('pemohon.proposals.store');
        Route::delete('/pemohon/proposals/{id}', [PengajuanSuratController::class, 'destroy'])->name('pemohon.proposals.destroy');
        Route::get('/pemohon/proposals/{id}', [PengajuanSuratController::class, 'show'])->name('pemohon.proposals.show');
        Route::put('/pemohon/proposal/update/{id}', [PengajuanSuratController::class, 'update'])->name('pemohon.proposals.update');
        Route::get('/pemohon/proposal/{id}/download-zip', [PengajuanSuratController::class, 'downloadZip'])->name('pemohon.proposals.downloadZip');

        Route::get('/pemohon/surat-tugas', [SuratTugasController::class, 'index'])->name('pemohon.surat-tugas.index');
        Route::get('/pemohon/surat-tugas/create', [SuratTugasController::class, 'create'])->name('pemohon.surat-tugas.create');
        Route::post('/pemohon/surat-tugas', [SuratTugasController::class, 'store'])->name('pemohon.surat-tugas.store');
        Route::get('/pemohon/surat-tugas/{id}', [SuratTugasController::class, 'show'])->name('pemohon.surat-tugas.show');
        Route::put('/pemohon/surat-tugas/{id}', [SuratTugasController::class, 'update'])->name('pemohon.surat-tugas.update');
        Route::delete('/pemohon/surat-tugas/{id}', [SuratTugasController::class, 'destroy'])->name('pemohon.surat-tugas.destroy');
        Route::get('/pemohon/proposal/{id}/pdf', [PengajuanSuratController::class, 'exportPdf'])->name('pemohon.proposals.pdf');
        Route::get('pemohon/proposal/{id}/word', [PengajuanSuratController::class, 'exportWord'])->name('pemohon.proposals.word');
        Route::get('/pemohon/surat-tugas/{id}/pdf', [SuratTugasController::class, 'exportPdf'])->name('pemohon.surat-tugas.pdf');
        Route::get('/pemohon/surat-tugas/{id}/word', [SuratTugasController::class, 'exportWord'])->name('pemohon.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Tata Usaha'])->group(function () {
        Route::get('/tu/dashboard', [TUController::class, 'dashboard'])->name('tu.dashboard');

        Route::get('/tu/proposals', [ProposalMasukController::class, 'index'])->name('tu.proposals.index');
        Route::put('/tu/proposals/{id}/update-status', [ProposalMasukController::class, 'updateStatus'])->name('tu.proposals.update-status');

        // Routes untuk fitur manajemen proposal TU
        Route::get('/tu/proposal', [PengajuanProposalController::class, 'index'])->name('tu.proposals.indexpengajuan');
        Route::get('/tu/proposal/create', [PengajuanProposalController::class, 'create'])->name('tu.proposals.create');
        Route::post('/tu/proposal', [PengajuanProposalController::class, 'store'])->name('tu.proposals.store');
        Route::get('/tu/proposal/{proposal}/edit', [PengajuanProposalController::class, 'edit'])->name('tu.proposals.edit');
        Route::put('/tu/proposal/{proposal}', [PengajuanProposalController::class, 'update'])->name('tu.proposals.update');
        Route::delete('/tu/proposal/{proposal}', [PengajuanProposalController::class, 'destroy'])->name('tu.proposals.destroy');
        Route::get('/tu/proposal/{id}/download-zip', [PengajuanProposalController::class, 'downloadZip'])->name('tu.proposals.downloadZip');

        // Route untuk fitur Disposisi Proposal
        Route::get('/tu/disposisi-proposal', [DisposisiController::class, 'index'])->name('tu.disposisi.index');
        Route::get('/tu/disposisi-proposal/{proposal}/edit', [DisposisiController::class, 'edit'])->name('tu.disposisi.show');
        Route::put('/tu/disposisi-proposal/{proposal}', [DisposisiController::class, 'update'])->name('tu.disposisi.update');
        // Route untuk menyelesaikan proposal
        Route::get('/tu/disposisi-proposal/{id}/reject', [DisposisiController::class, 'rejectForm'])->name('tu.disposisi.reject');
        Route::put('tu/disposisi/reject/submit/{id}', [DisposisiController::class, 'rejectSubmit'])->name('tu.disposisi.reject.submit');
        Route::get('/tu/disposisi-proposal/{id}/selesaikan', [DisposisiController::class, 'selesaikan'])->name('tu.disposisi.selesaikan');

        // Monitoring Route
        Route::get('/tu/monitoring', [MonitoringTUController::class, 'index'])->name('tu.monitoring.index');

        // Route Arsip Surat
        Route::get('/tu/arsip-surat', [ArsipSuratController::class, 'index'])->name('tu.arsip.index');
        Route::delete('/tu/arsip-surat/{proposal}', [ArsipSuratController::class, 'destroy'])->name('tu.arsip.destroy');

        // Alasan Penolakan 
        // Route::get('proposals/{proposal}/reject', [PengajuanProposalController::class, 'rejectForm'])->name('tu.proposals.rejectForm');
        Route::put('proposals/{proposal}/reject', [PengajuanProposalController::class, 'reject'])->name('tu.proposals.reject');
        Route::post('/tu/proposal/{id}/upload-sk', [DisposisiController::class, 'uploadSK'])->name('tu.proposal.upload-sk');

        Route::get('/tu/proposal/{id}/pdf', [TuExportController::class, 'exportPdfSuratMasuk'])->name('tu.proposals.pdf');
        Route::get('/tu/surat-tugas/{id}/pdf', [TuExportController::class, 'exportPdfSuratTugas'])->name('tu.surat-tugas.pdf');
        Route::get('/tu/proposal/{id}/word', [TuExportController::class, 'exportWordSuratMasuk'])->name('tu.proposals.word');
        Route::get('/tu/surat-tugas/{id}/word', [TuExportController::class, 'exportWordSuratTugas'])->name('tu.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Dekanat'])->group(function () {
        // Dekan routes
        Route::get('/dekanat/dashboard', [DekanController::class, 'dashboard'])->name('dekanat.dashboard');

        Route::get('/dekanat/disposisi', [DekanDisposisiController::class, 'index'])->name('dekanat.disposisi.index');
        Route::get('/dekanat/monitoring', [MonitoringController::class, 'index'])->name('dekanat.monitoring.index');
        Route::get('/dekanat/disposisi/{id}/edit', [DekanDisposisiController::class, 'edit'])->name('dekanat.disposisi.edit');
        Route::put('/dekanat/disposisi/{proposal}', [DekanDisposisiController::class, 'updateDisposisi'])->name('dekanat.disposisi.updateDisposisi');
        Route::get('/dekanat/disposisi/{id}/reject', [DekanDisposisiController::class, 'reject'])->name('dekanat.disposisi.reject');
        Route::put('/dekanat/disposisi/{proposal}/reject', [DekanDisposisiController::class, 'updateReject'])->name('dekanat.disposisi.updateReject');
        Route::get('/dekanat/proposal/{id}/download-zip', [DekanDisposisiController::class, 'downloadZip'])->name('dekanat.proposals.downloadZip');
        Route::post('/dekanat/proposal/{id}/upload-sk', [DekanDisposisiController::class, 'uploadSK'])->name('dekanat.proposal.upload-sk');

        // Route Arsip Surat
        Route::get('/dekanat/arsip-surat', [ArsipSuratDekanController::class, 'index'])->name('dekanat.arsip.index');
        Route::delete('/dekanat/arsip-surat/{proposal}', [ArsipSuratDekanController::class, 'destroy'])->name('dekanat.arsip.destroy');

        // export
        Route::get('/dekanat/proposal/{id}/pdf', [DekanExportController::class, 'exportPdfSuratMasuk'])->name('dekanat.proposals.pdf');
        Route::get('/dekanat/surat-tugas/{id}/pdf', [DekanExportController::class, 'exportPdfSuratTugas'])->name('dekanat.surat-tugas.pdf');
        Route::get('/dekanat/proposal/{id}/word', [DekanExportController::class, 'exportWordSuratMasuk'])->name('dekanat.proposals.word');
        Route::get('/dekanat/surat-tugas/{id}/word', [DekanExportController::class, 'exportWordSuratTugas'])->name('dekanat.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:PLT'])->group(function () {
        // Plt routes
        Route::get('/plt/dashboard', [PltController::class, 'dashboard'])->name('plt.dashboard');

        Route::get('/plt/disposisi', [PltDisposisiController::class, 'index'])->name('plt.disposisi.index');
        Route::get('/plt/disposisi/{id}/edit', [PltDisposisiController::class, 'edit'])->name('plt.disposisi.edit');
        Route::put('/plt/disposisi/{proposal}', [PltDisposisiController::class, 'updateDisposisi'])->name('plt.disposisi.updateDisposisi');
        Route::get('/plt/disposisi/{id}/reject', [PltDisposisiController::class, 'reject'])->name('plt.disposisi.reject');
        Route::put('/plt/disposisi/{proposal}/reject', [PltDisposisiController::class, 'updateReject'])->name('plt.disposisi.updateReject');
        Route::get('/plt/proposal/{id}/download-zip', [PltDisposisiController::class, 'downloadZip'])->name('plt.proposals.downloadZip');
        Route::post('/plt/proposal/{id}/upload-sk', [PltDisposisiController::class, 'uploadSK'])->name('plt.proposal.upload-sk');

        // Monitoring Route
        Route::get('/plt/monitoring', [MonitoringPltController::class, 'index'])->name('plt.monitoring.index');

        // Route Arsip Surat
        Route::get('/plt/arsip-surat', [ArsipSuratPltController::class, 'index'])->name('plt.arsip.index');
        Route::delete('/plt/arsip-surat/{proposal}', [ArsipSuratPltController::class, 'destroy'])->name('plt.arsip.destroy');

        //export
        Route::get('/plt/proposal/{id}/pdf', [PltExportController::class, 'exportPdfSuratMasuk'])->name('plt.proposals.pdf');
        Route::get('/plt/surat-tugas/{id}/pdf', [PltExportController::class, 'exportPdfSuratTugas'])->name('plt.surat-tugas.pdf');
        Route::get('/plt/proposal/{id}/word', [PltExportController::class, 'exportWordSuratMasuk'])->name('plt.proposals.word');
        Route::get('/plt/surat-tugas/{id}/word', [PltExportController::class, 'exportWordSuratTugas'])->name('plt.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Akademik'])->group(function () {
        // Plt routes
        Route::get('/akademik/dashboard', [AkademikController::class, 'dashboard'])->name('akademik.dashboard');

        Route::get('/akademik/disposisi', [AkademikDisposisiController::class, 'index'])->name('akademik.disposisi.index');
        Route::get('/akademik/disposisi/{id}/edit', [AkademikDisposisiController::class, 'edit'])->name('akademik.disposisi.edit');
        Route::put('/akademik/disposisi/{proposal}', [AkademikDisposisiController::class, 'updateDisposisi'])->name('akademik.disposisi.updateDisposisi');
        Route::get('/akademik/disposisi/{id}/reject', [AkademikDisposisiController::class, 'reject'])->name('akademik.disposisi.reject');
        Route::put('/akademik/disposisi/{proposal}/reject', [AkademikDisposisiController::class, 'updateReject'])->name('akademik.disposisi.updateReject');
        Route::get('/akademik/proposal/{id}/download-zip', [AkademikDisposisiController::class, 'downloadZip'])->name('akademik.proposals.downloadZip');
        Route::post('/akademik/proposal/{id}/upload-sk', [AkademikDisposisiController::class, 'uploadSK'])->name('akademik.proposal.upload-sk');

        // Monitoring Route
        Route::get('/akademik/monitoring', [MonitoringAkademikController::class, 'index'])->name('akademik.monitoring.index');

        // Route Arsip Surat
        Route::get('/akademik/arsip-surat', [ArsipSuratAkademikController::class, 'index'])->name('akademik.arsip.index');
        Route::delete('/akademik/arsip-surat/{proposal}', [ArsipSuratAkademikController::class, 'destroy'])->name('akademik.arsip.destroy');

        // export
        Route::get('/akademik/proposal/{id}/pdf', [AkademikExportController::class, 'exportPdfSuratMasuk'])->name('akademik.proposals.pdf');
        Route::get('/akademik/surat-tugas/{id}/pdf', [AkademikExportController::class, 'exportPdfSuratTugas'])->name('akademik.surat-tugas.pdf');
        Route::get('/akademik/proposal/{id}/word', [AkademikExportController::class, 'exportWordSuratMasuk'])->name('akademik.proposals.word');
        Route::get('/akademik/surat-tugas/{id}/word', [AkademikExportController::class, 'exportWordSuratTugas'])->name('akademik.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Umum'])->group(function () {
        // Plt routes
        Route::get('/umum/dashboard', [UmumController::class, 'dashboard'])->name('umum.dashboard');

        Route::get('/umum/disposisi', [UmumDisposisiController::class, 'index'])->name('umum.disposisi.index');
        Route::get('/umum/disposisi/{id}/edit', [UmumDisposisiController::class, 'edit'])->name('umum.disposisi.edit');
        Route::put('/umum/disposisi/{proposal}', [UmumDisposisiController::class, 'updateDisposisi'])->name('umum.disposisi.updateDisposisi');
        Route::get('/umum/disposisi/{id}/reject', [UmumDisposisiController::class, 'reject'])->name('umum.disposisi.reject');
        Route::put('/umum/disposisi/{proposal}/reject', [UmumDisposisiController::class, 'updateReject'])->name('umum.disposisi.updateReject');
        Route::get('/umum/proposal/{id}/download-zip', [UmumDisposisiController::class, 'downloadZip'])->name('umum.proposals.downloadZip');
        Route::post('umum/proposal/{id}/upload-sk', [UmumDisposisiController::class, 'uploadSK'])->name('umum.proposal.upload-sk');

        // Monitoring Route
        Route::get('/umum/monitoring', [MonitoringUmumController::class, 'index'])->name('umum.monitoring.index');

        // Route Arsip Surat
        Route::get('/umum/arsip-surat', [ArsipSuratUmumController::class, 'index'])->name('umum.arsip.index');
        Route::delete('/umum/arsip-surat/{proposal}', [ArsipSuratUmumController::class, 'destroy'])->name('umum.arsip.destroy');

        //export
        Route::get('/umum/proposal/{id}/pdf', [UmumExportController::class, 'exportPdfSuratMasuk'])->name('umum.proposals.pdf');
        Route::get('/umum/surat-tugas/{id}/pdf', [UmumExportController::class, 'exportPdfSuratTugas'])->name('umum.surat-tugas.pdf');
        Route::get('/umum/proposal/{id}/word', [UmumExportController::class, 'exportWordSuratMasuk'])->name('umum.proposals.word');
        Route::get('/umum/surat-tugas/{id}/word', [UmumExportController::class, 'exportWordSuratTugas'])->name('umum.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Perpus'])->group(function () {
        // Plt routes
        Route::get('/perpus/dashboard', [PerpusController::class, 'dashboard'])->name('perpus.dashboard');

        Route::get('/perpus/disposisi', [PerpusDisposisiController::class, 'index'])->name('perpus.disposisi.index');
        Route::get('/perpus/disposisi/{id}/edit', [PerpusDisposisiController::class, 'edit'])->name('perpus.disposisi.edit');
        Route::put('/perpus/disposisi/{proposal}', [PerpusDisposisiController::class, 'updateDisposisi'])->name('perpus.disposisi.updateDisposisi');
        Route::get('/perpus/disposisi/{id}/reject', [PerpusDisposisiController::class, 'reject'])->name('perpus.disposisi.reject');
        Route::put('/perpus/disposisi/{proposal}/reject', [PerpusDisposisiController::class, 'updateReject'])->name('perpus.disposisi.updateReject');
        Route::get('/perpus/proposal/{id}/download-zip', [PerpusDisposisiController::class, 'downloadZip'])->name('perpus.proposals.downloadZip');
        Route::post('perpus/proposal/{id}/upload-sk', [PerpusDisposisiController::class, 'uploadSK'])->name('perpus.proposal.upload-sk');

        // Monitoring Route
        Route::get('/perpus/monitoring', [MonitoringPerpusController::class, 'index'])->name('perpus.monitoring.index');

        // Route Arsip Surat
        Route::get('/perpus/arsip-surat', [ArsipSuratPerpusController::class, 'index'])->name('perpus.arsip.index');
        Route::delete('/perpus/arsip-surat/{proposal}', [ArsipSuratPerpusController::class, 'destroy'])->name('perpus.arsip.destroy');

        //export
        Route::get('/perpus/proposal/{id}/pdf', [PerpusExportController::class, 'exportPdfSuratMasuk'])->name('perpus.proposals.pdf');
        Route::get('/perpus/surat-tugas/{id}/pdf', [PerpusExportController::class, 'exportPdfSuratTugas'])->name('perpus.surat-tugas.pdf');
        Route::get('/perpus/proposal/{id}/word', [PerpusExportController::class, 'exportWordSuratMasuk'])->name('perpus.proposals.word');
        Route::get('/perpus/surat-tugas/{id}/word', [PerpusExportController::class, 'exportWordSuratTugas'])->name('perpus.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Teknik Informatika'])->group(function () {
        // Dekan routes
        Route::get('/prodi-teknik-informatika/dashboard', [ProdiTIController::class, 'dashboard'])->name('prodi-teknik-informatika.dashboard');

        Route::get('/prodi-teknik-informatika/disposisi', [ProdiTIDisposisiController::class, 'index'])->name('prodi-teknik-informatika.disposisi.index');
        Route::get('/prodi-teknik-informatika/disposisi/{id}/edit', [ProdiTIDisposisiController::class, 'edit'])->name('prodi-teknik-informatika.disposisi.edit');
        Route::put('/prodi-teknik-informatika/disposisi/{proposal}', [ProdiTIDisposisiController::class, 'updateDisposisi'])->name('prodi-teknik-informatika.disposisi.updateDisposisi');
        Route::get('/prodi-teknik-informatika/disposisi/{id}/reject', [ProdiTIDisposisiController::class, 'reject'])->name('prodi-teknik-informatika.disposisi.reject');
        Route::put('/prodi-teknik-informatika/disposisi/{proposal}/reject', [ProdiTIDisposisiController::class, 'updateReject'])->name('prodi-teknik-informatika.disposisi.updateReject');
        Route::get('/prodi-teknik-informatika/proposal/{id}/download-zip', [ProdiTIDisposisiController::class, 'downloadZip'])->name('prodi-teknik-informatika.proposals.downloadZip');
        Route::post('prodi-teknik-informatika/proposal/{id}/upload-sk', [ProdiTIDisposisiController::class, 'uploadSK'])->name('prodi-teknik-informatika.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-teknik-informatika/monitoring', [MonitoringProdiTIController::class, 'index'])->name('prodi-teknik-informatika.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-teknik-informatika/arsip-surat', [ArsipSuratProdiTIController::class, 'index'])->name('prodi-teknik-informatika.arsip.index');
        Route::delete('/prodi-teknik-informatika/arsip-surat/{proposal}', [ArsipSuratProdiTIController::class, 'destroy'])->name('prodi-teknik-informatika.arsip.destroy');

        //export
        Route::get('/prodi-teknik-informatika/proposal/{id}/pdf', [ProdiTIExportController::class, 'exportPdfSuratMasuk'])->name('prodi-teknik-informatika.proposals.pdf');
        Route::get('/prodi-teknik-informatika/surat-tugas/{id}/pdf', [ProdiTIExportController::class, 'exportPdfSuratTugas'])->name('prodi-teknik-informatika.surat-tugas.pdf');
        Route::get('/prodi-teknik-informatika/proposal/{id}/word', [ProdiTIExportController::class, 'exportWordSuratMasuk'])->name('prodi-teknik-informatika.proposals.word');
        Route::get('/prodi-teknik-informatika/surat-tugas/{id}/word', [ProdiTIExportController::class, 'exportWordSuratTugas'])->name('prodi-teknik-informatika.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Agribisnis'])->group(function () {
        // Dekan routes
        Route::get('/prodi-agribisnis/dashboard', [ProdiAgribisnisController::class, 'dashboard'])->name('prodi-agribisnis.dashboard');

        Route::get('/prodi-agribisnis/disposisi', [ProdiAgribisnisDisposisiController::class, 'index'])->name('prodi-agribisnis.disposisi.index');
        Route::get('/prodi-agribisnis/disposisi/{id}/edit', [ProdiAgribisnisDisposisiController::class, 'edit'])->name('prodi-agribisnis.disposisi.edit');
        Route::put('/prodi-agribisnis/disposisi/{proposal}', [ProdiAgribisnisDisposisiController::class, 'updateDisposisi'])->name('prodi-agribisnis.disposisi.updateDisposisi');
        Route::get('/prodi-agribisnis/disposisi/{id}/reject', [ProdiAgribisnisDisposisiController::class, 'reject'])->name('prodi-agribisnis.disposisi.reject');
        Route::put('/prodi-agribisnis/disposisi/{proposal}/reject', [ProdiAgribisnisDisposisiController::class, 'updateReject'])->name('prodi-agribisnis.disposisi.updateReject');
        Route::get('/prodi-agribisnis/proposal/{id}/download-zip', [ProdiAgribisnisDisposisiController::class, 'downloadZip'])->name('prodi-agribisnis.proposals.downloadZip');
        Route::post('prodi-agribisnis/proposal/{id}/upload-sk', [ProdiAgribisnisDisposisiController::class, 'uploadSK'])->name('prodi-agribisnis.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-agribisnis/monitoring', [MonitoringProdiAgribisnisController::class, 'index'])->name('prodi-agribisnis.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-agribisnis/arsip-surat', [ArsipSuratProdiAgribisnisController::class, 'index'])->name('prodi-agribisnis.arsip.index');
        Route::delete('/prodi-agribisnis/arsip-surat/{proposal}', [ArsipSuratProdiAgribisnisController::class, 'destroy'])->name('prodi-agribisnis.arsip.destroy');
        
        //export
        Route::get('/prodi-agribisnis/proposal/{id}/pdf', [ProdiAgribisnisExportController::class, 'exportPdfSuratMasuk'])->name('prodi-agribisnis.proposals.pdf');
        Route::get('/prodi-agribisnis/surat-tugas/{id}/pdf', [ProdiAgribisnisExportController::class, 'exportPdfSuratTugas'])->name('prodi-agribisnis.surat-tugas.pdf');
        Route::get('/prodi-agribisnis/proposal/{id}/word', [ProdiAgribisnisExportController::class, 'exportWordSuratMasuk'])->name('prodi-agribisnis.proposals.word');
        Route::get('/prodi-agribisnis/surat-tugas/{id}/word', [ProdiAgribisnisExportController::class, 'exportWordSuratTugas'])->name('prodi-agribisnis.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Sistem Informasi'])->group(function () {
        // Dekan routes
        Route::get('/prodi-sistem-informasi/dashboard', [ProdiSIController::class, 'dashboard'])->name('prodi-sistem-informasi.dashboard');

        Route::get('/prodi-sistem-informasi/disposisi', [ProdiSIDisposisiController::class, 'index'])->name('prodi-sistem-informasi.disposisi.index');
        Route::get('/prodi-sistem-informasi/disposisi/{id}/edit', [ProdiSIDisposisiController::class, 'edit'])->name('prodi-sistem-informasi.disposisi.edit');
        Route::put('/prodi-sistem-informasi/disposisi/{proposal}', [ProdiSIDisposisiController::class, 'updateDisposisi'])->name('prodi-sistem-informasi.disposisi.updateDisposisi');
        Route::get('/prodi-sistem-informasi/disposisi/{id}/reject', [ProdiSIDisposisiController::class, 'reject'])->name('prodi-sistem-informasi.disposisi.reject');
        Route::put('/prodi-sistem-informasi/disposisi/{proposal}/reject', [ProdiSIDisposisiController::class, 'updateReject'])->name('prodi-sistem-informasi.disposisi.updateReject');
        Route::get('/prodi-sistem-informasi/proposal/{id}/download-zip', [ProdiSIDisposisiController::class, 'downloadZip'])->name('prodi-sistem-informasi.proposals.downloadZip');
        Route::post('prodi-sistem-informasi/proposal/{id}/upload-sk', [ProdiSIDisposisiController::class, 'uploadSK'])->name('prodi-sistem-informasi.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-sistem-informasi/monitoring', [MonitoringProdiSIController::class, 'index'])->name('prodi-sistem-informasi.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-sistem-informasi/arsip-surat', [ArsipSuratProdiSIController::class, 'index'])->name('prodi-sistem-informasi.arsip.index');
        Route::delete('/prodi-sistem-informasi/arsip-surat/{proposal}', [ArsipSuratProdiSIController::class, 'destroy'])->name('prodi-sistem-informasi.arsip.destroy');

        //export
        Route::get('/prodi-sistem-informasi/proposal/{id}/pdf', [ProdiSIExportController::class, 'exportPdfSuratMasuk'])->name('prodi-sistem-informasi.proposals.pdf');
        Route::get('/prodi-sistem-informasi/surat-tugas/{id}/pdf', [ProdiSIExportController::class, 'exportPdfSuratTugas'])->name('prodi-sistem-informasi.surat-tugas.pdf');
        Route::get('/prodi-sistem-informasi/proposal/{id}/word', [ProdiSIExportController::class, 'exportWordSuratMasuk'])->name('prodi-sistem-informasi.proposals.word');
        Route::get('/prodi-sistem-informasi/surat-tugas/{id}/word', [ProdiSIExportController::class, 'exportWordSuratTugas'])->name('prodi-sistem-informasi.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Matematika'])->group(function () {
        // Dekan routes
        Route::get('/prodi-matematika/dashboard', [ProdiMatematikaController::class, 'dashboard'])->name('prodi-matematika.dashboard');

        Route::get('/prodi-matematika/disposisi', [ProdiMatematikaDisposisiController::class, 'index'])->name('prodi-matematika.disposisi.index');
        Route::get('/prodi-matematika/disposisi/{id}/edit', [ProdiMatematikaDisposisiController::class, 'edit'])->name('prodi-matematika.disposisi.edit');
        Route::put('/prodi-matematika/disposisi/{proposal}', [ProdiMatematikaDisposisiController::class, 'updateDisposisi'])->name('prodi-matematika.disposisi.updateDisposisi');
        Route::get('/prodi-matematika/disposisi/{id}/reject', [ProdiMatematikaDisposisiController::class, 'reject'])->name('prodi-matematika.disposisi.reject');
        Route::put('/prodi-matematika/disposisi/{proposal}/reject', [ProdiMatematikaDisposisiController::class, 'updateReject'])->name('prodi-matematika.disposisi.updateReject');
        Route::get('/prodi-matematika/proposal/{id}/download-zip', [ProdiMatematikaDisposisiController::class, 'downloadZip'])->name('prodi-matematika.proposals.downloadZip');
        Route::post('prodi-matematika/proposal/{id}/upload-sk', [ProdiMatematikaDisposisiController::class, 'uploadSK'])->name('prodi-matematika.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-matematika/monitoring', [MonitoringProdiMatematikaController::class, 'index'])->name('prodi-matematika.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-matematika/arsip-surat', [ArsipSuratProdiMatematikaController::class, 'index'])->name('prodi-matematika.arsip.index');
        Route::delete('/prodi-matematika/arsip-surat/{proposal}', [ArsipSuratProdiMatematikaController::class, 'destroy'])->name('prodi-matematika.arsip.destroy');

        //export
        Route::get('/prodi-matematika/proposal/{id}/pdf', [ProdiMatematikaExportController::class, 'exportPdfSuratMasuk'])->name('prodi-matematika.proposals.pdf');
        Route::get('/prodi-matematika/surat-tugas/{id}/pdf', [ProdiMatematikaExportController::class, 'exportPdfSuratTugas'])->name('prodi-matematika.surat-tugas.pdf');
        Route::get('/prodi-matematika/proposal/{id}/word', [ProdiMatematikaExportController::class, 'exportWordSuratMasuk'])->name('prodi-matematika.proposals.word');
        Route::get('/prodi-matematika/surat-tugas/{id}/word', [ProdiMatematikaExportController::class, 'exportWordSuratTugas'])->name('prodi-matematika.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Fisika'])->group(function () {
        // Dekan routes
        Route::get('/prodi-fisika/dashboard', [ProdiFisikaController::class, 'dashboard'])->name('prodi-fisika.dashboard');

        Route::get('/prodi-fisika/disposisi', [ProdiFisikaDisposisiController::class, 'index'])->name('prodi-fisika.disposisi.index');
        Route::get('/prodi-fisika/disposisi/{id}/edit', [ProdiFisikaDisposisiController::class, 'edit'])->name('prodi-fisika.disposisi.edit');
        Route::put('/prodi-fisika/disposisi/{proposal}', [ProdiFisikaDisposisiController::class, 'updateDisposisi'])->name('prodi-fisika.disposisi.updateDisposisi');
        Route::get('/prodi-fisika/disposisi/{id}/reject', [ProdiFisikaDisposisiController::class, 'reject'])->name('prodi-fisika.disposisi.reject');
        Route::put('/prodi-fisika/disposisi/{proposal}/reject', [ProdiFisikaDisposisiController::class, 'updateReject'])->name('prodi-fisika.disposisi.updateReject');
        Route::get('/prodi-fisika/proposal/{id}/download-zip', [ProdiFisikaDisposisiController::class, 'downloadZip'])->name('prodi-fisika.proposals.downloadZip');
        Route::post('prodi-fisika/proposal/{id}/upload-sk', [ProdiFisikaDisposisiController::class, 'uploadSK'])->name('prodi-fisika.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-fisika/monitoring', [MonitoringProdiFisikaController::class, 'index'])->name('prodi-fisika.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-fisika/arsip-surat', [ArsipSuratProdiFisikaController::class, 'index'])->name('prodi-fisika.arsip.index');
        Route::delete('/prodi-fisika/arsip-surat/{proposal}', [ArsipSuratProdiFisikaController::class, 'destroy'])->name('prodi-fisika.arsip.destroy');

        //export
        Route::get('/prodi-fisika/proposal/{id}/pdf', [ProdiFisikaExportController::class, 'exportPdfSuratMasuk'])->name('prodi-fisika.proposals.pdf');
        Route::get('/prodi-fisika/surat-tugas/{id}/pdf', [ProdiFisikaExportController::class, 'exportPdfSuratTugas'])->name('prodi-fisika.surat-tugas.pdf');
        Route::get('/prodi-fisika/proposal/{id}/word', [ProdiFisikaExportController::class, 'exportWordSuratMasuk'])->name('prodi-fisika.proposals.word');
        Route::get('/prodi-fisika/surat-tugas/{id}/word', [ProdiFisikaExportController::class, 'exportWordSuratTugas'])->name('prodi-fisika.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Kimia'])->group(function () {
        // Dekan routes
        Route::get('/prodi-kimia/dashboard', [ProdiKimiaController::class, 'dashboard'])->name('prodi-kimia.dashboard');

        Route::get('/prodi-kimia/disposisi', [ProdiKimiaDisposisiController::class, 'index'])->name('prodi-kimia.disposisi.index');
        Route::get('/prodi-kimia/disposisi/{id}/edit', [ProdiKimiaDisposisiController::class, 'edit'])->name('prodi-kimia.disposisi.edit');
        Route::put('/prodi-kimia/disposisi/{proposal}', [ProdiKimiaDisposisiController::class, 'updateDisposisi'])->name('prodi-kimia.disposisi.updateDisposisi');
        Route::get('/prodi-kimia/disposisi/{id}/reject', [ProdiKimiaDisposisiController::class, 'reject'])->name('prodi-kimia.disposisi.reject');
        Route::put('/prodi-kimia/disposisi/{proposal}/reject', [ProdiKimiaDisposisiController::class, 'updateReject'])->name('prodi-kimia.disposisi.updateReject');
        Route::get('/prodi-kimia/proposal/{id}/download-zip', [ProdiKimiaDisposisiController::class, 'downloadZip'])->name('prodi-kimia.proposals.downloadZip');
        Route::post('prodi-kimia/proposal/{id}/upload-sk', [ProdiKimiaDisposisiController::class, 'uploadSK'])->name('prodi-kimia.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-kimia/monitoring', [MonitoringProdiKimiaController::class, 'index'])->name('prodi-kimia.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-kimia/arsip-surat', [ArsipSuratProdiKimiaController::class, 'index'])->name('prodi-kimia.arsip.index');
        Route::delete('/prodi-kimia/arsip-surat/{proposal}', [ArsipSuratProdiKimiaController::class, 'destroy'])->name('prodi-kimia.arsip.destroy');

        //export
        Route::get('/prodi-kimia/proposal/{id}/pdf', [ProdiKimiaExportController::class, 'exportPdfSuratMasuk'])->name('prodi-kimia.proposals.pdf');
        Route::get('/prodi-kimia/surat-tugas/{id}/pdf', [ProdiKimiaExportController::class, 'exportPdfSuratTugas'])->name('prodi-kimia.surat-tugas.pdf');
        Route::get('/prodi-kimia/proposal/{id}/word', [ProdiKimiaExportController::class, 'exportWordSuratMasuk'])->name('prodi-kimia.proposals.word');
        Route::get('/prodi-kimia/surat-tugas/{id}/word', [ProdiKimiaExportController::class, 'exportWordSuratTugas'])->name('prodi-kimia.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Biologi'])->group(function () {
        // Dekan routes
        Route::get('/prodi-biologi/dashboard', [ProdiBiologiController::class, 'dashboard'])->name('prodi-biologi.dashboard');

        Route::get('/prodi-biologi/disposisi', [ProdiBiologiDisposisiController::class, 'index'])->name('prodi-biologi.disposisi.index');
        Route::get('/prodi-biologi/disposisi/{id}/edit', [ProdiBiologiDisposisiController::class, 'edit'])->name('prodi-biologi.disposisi.edit');
        Route::put('/prodi-biologi/disposisi/{proposal}', [ProdiBiologiDisposisiController::class, 'updateDisposisi'])->name('prodi-biologi.disposisi.updateDisposisi');
        Route::get('/prodi-biologi/disposisi/{id}/reject', [ProdiBiologiDisposisiController::class, 'reject'])->name('prodi-biologi.disposisi.reject');
        Route::put('/prodi-biologi/disposisi/{proposal}/reject', [ProdiBiologiDisposisiController::class, 'updateReject'])->name('prodi-biologi.disposisi.updateReject');
        Route::get('/prodi-biologi/proposal/{id}/download-zip', [ProdiBiologiDisposisiController::class, 'downloadZip'])->name('prodi-biologi.proposals.downloadZip');
        Route::post('prodi-biologi/proposal/{id}/upload-sk', [ProdiBiologiDisposisiController::class, 'uploadSK'])->name('prodi-biologi.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-biologi/monitoring', [MonitoringProdiBiologiController::class, 'index'])->name('prodi-biologi.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-biologi/arsip-surat', [ArsipSuratProdiBiologiController::class, 'index'])->name('prodi-biologi.arsip.index');
        Route::delete('/prodi-biologi/arsip-surat/{proposal}', [ArsipSuratProdiBiologiController::class, 'destroy'])->name('prodi-biologi.arsip.destroy');

        //export
        Route::get('/prodi-biologi/proposal/{id}/pdf', [ProdiBiologiExportController::class, 'exportPdfSuratMasuk'])->name('prodi-biologi.proposals.pdf');
        Route::get('/prodi-biologi/surat-tugas/{id}/pdf', [ProdiBiologiExportController::class, 'exportPdfSuratTugas'])->name('prodi-biologi.surat-tugas.pdf');
        Route::get('/prodi-biologi/proposal/{id}/word', [ProdiBiologiExportController::class, 'exportWordSuratMasuk'])->name('prodi-biologi.proposals.word');
        Route::get('/prodi-biologi/surat-tugas/{id}/word', [ProdiBiologiExportController::class, 'exportWordSuratTugas'])->name('prodi-biologi.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Prodi Teknik Pertambangan'])->group(function () {
        // Dekan routes
        Route::get('/prodi-teknik-pertambangan/dashboard', [ProdiTPController::class, 'dashboard'])->name('prodi-teknik-pertambangan.dashboard');

        Route::get('/prodi-teknik-pertambangan/disposisi', [ProdiTPDisposisiController::class, 'index'])->name('prodi-teknik-pertambangan.disposisi.index');
        Route::get('/prodi-teknik-pertambangan/disposisi/{id}/edit', [ProdiTPDisposisiController::class, 'edit'])->name('prodi-teknik-pertambangan.disposisi.edit');
        Route::put('/prodi-teknik-pertambangan/disposisi/{proposal}', [ProdiTPDisposisiController::class, 'updateDisposisi'])->name('prodi-teknik-pertambangan.disposisi.updateDisposisi');
        Route::get('/prodi-teknik-pertambangan/disposisi/{id}/reject', [ProdiTPDisposisiController::class, 'reject'])->name('prodi-teknik-pertambangan.disposisi.reject');
        Route::put('/prodi-teknik-pertambangan/disposisi/{proposal}/reject', [ProdiTPDisposisiController::class, 'updateReject'])->name('prodi-teknik-pertambangan.disposisi.updateReject');
        Route::get('/prodi-teknik-pertambangan/proposal/{id}/download-zip', [ProdiTPDisposisiController::class, 'downloadZip'])->name('prodi-teknik-pertambangan.proposals.downloadZip');
        Route::post('prodi-teknik-pertambangan/proposal/{id}/upload-sk', [ProdiTPDisposisiController::class, 'uploadSK'])->name('prodi-teknik-pertambangan.proposal.upload-sk');

        // Monitoring Route
        Route::get('/prodi-teknik-pertambangan/monitoring', [MonitoringProdiTPController::class, 'index'])->name('prodi-teknik-pertambangan.monitoring.index');

        // Route Arsip Surat
        Route::get('/prodi-teknik-pertambangan/arsip-surat', [ArsipSuratProdiTPController::class, 'index'])->name('prodi-teknik-pertambangan.arsip.index');
        Route::delete('/prodi-teknik-pertambangan/arsip-surat/{proposal}', [ArsipSuratProdiTPController::class, 'destroy'])->name('prodi-teknik-pertambangan.arsip.destroy');

        //export
        Route::get('/prodi-teknik-pertambangan/proposal/{id}/pdf', [ProdiTPExportController::class, 'exportPdfSuratMasuk'])->name('prodi-teknik-pertambangan.proposals.pdf');
        Route::get('/prodi-teknik-pertambangan/surat-tugas/{id}/pdf', [ProdiTPExportController::class, 'exportPdfSuratTugas'])->name('prodi-teknik-pertambangan.surat-tugas.pdf');
        Route::get('/prodi-teknik-pertambangan/proposal/{id}/word', [ProdiTPExportController::class, 'exportWordSuratMasuk'])->name('prodi-teknik-pertambangan.proposals.word');
        Route::get('/prodi-teknik-pertambangan/surat-tugas/{id}/word', [ProdiTPExportController::class, 'exportWordSuratTugas'])->name('prodi-teknik-pertambangan.surat-tugas.word');
    });

    Route::middleware(['auth', 'role:Keuangan'])->group(function () {
        // Keuangan routes
        Route::get('/keuangan/dashboard', [KeuanganController::class, 'dashboard'])->name('keuangan.dashboard');

        Route::get('/keuangan/disposisi', [KeuanganDisposisiController::class, 'index'])->name('keuangan.disposisi.index');
        Route::get('/keuangan/disposisi/{id}/edit', [KeuanganDisposisiController::class, 'edit'])->name('keuangan.disposisi.edit');
        Route::put('/keuangan/disposisi/{proposal}', [KeuanganDisposisiController::class, 'updateDisposisi'])->name('keuangan.disposisi.updateDisposisi');
        Route::get('/keuangan/disposisi/{id}/reject', [KeuanganDisposisiController::class, 'reject'])->name('keuangan.disposisi.reject');
        Route::put('/keuangan/disposisi/{proposal}/reject', [KeuanganDisposisiController::class, 'updateReject'])->name('keuangan.disposisi.updateReject');
        Route::get('/keuangan/proposal/{id}/download-zip', [KeuanganDisposisiController::class, 'downloadZip'])->name('keuangan.proposals.downloadZip');
        Route::post('keuangan/proposal/{id}/upload-sk', [KeuanganDisposisiController::class, 'uploadSK'])->name('keuangan.proposal.upload-sk');

        // Monitoring Route
        Route::get('/keuangan/monitoring', [MonitoringKeuanganController::class, 'index'])->name('keuangan.monitoring.index');

        // Route Arsip Surat
        Route::get('/keuangan/arsip-surat', [ArsipSuratKeuanganController::class, 'index'])->name('keuangan.arsip.index');
        Route::delete('/keuangan/arsip-surat/{proposal}', [ArsipSuratKeuanganController::class, 'destroy'])->name('keuangan.arsip.destroy');

        //export
        Route::get('/keuangan/proposal/{id}/pdf', [KeuanganExportController::class, 'exportPdfSuratMasuk'])->name('keuangan.proposals.pdf');
        Route::get('/keuangan/surat-tugas/{id}/pdf', [KeuanganExportController::class, 'exportPdfSuratTugas'])->name('keuangan.surat-tugas.pdf');
        Route::get('/keuangan/proposal/{id}/word', [KeuanganExportController::class, 'exportWordSuratMasuk'])->name('keuangan.proposals.word');
        Route::get('/keuangan/surat-tugas/{id}/word', [KeuanganExportController::class, 'exportWordSuratTugas'])->name('keuangan.surat-tugas.word');
    });
});

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
