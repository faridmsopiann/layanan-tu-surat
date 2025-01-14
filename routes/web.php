<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Dekan\DekanController;
use App\Http\Controllers\Dekan\DisposisiController as DekanDisposisiController;
use App\Http\Controllers\Dekan\MonitoringController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Keuangan\KeuanganController;
use App\Http\Controllers\Keuangan\KeuanganDisposisiController;
use App\Http\Controllers\Keuangan\MonitoringController as KeuanganMonitoringController;
use App\Http\Controllers\Pemohon\PemohonController;
use App\Http\Controllers\Pemohon\PengajuanSuratController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\TU\DisposisiController;
use App\Http\Controllers\TU\PengajuanProposalController;
use App\Http\Controllers\TU\ProposalMasukController;
use App\Http\Controllers\TU\TUController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/qr-login', [QRCodeController::class, 'generateLoginQR'])->name('qr.login');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'pemohon') {
            return redirect()->route('pemohon.dashboard');
        } elseif (auth()->user()->role === 'tu') {
            return redirect()->route('tu.dashboard');
        } elseif (auth()->user()->role === 'dekan') {
            return redirect()->route('dekan.dashboard');
        } elseif (auth()->user()->role === 'keuangan') {
            return redirect()->route('keuangan.dashboard');
        }
    })->name('dashboard');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Routes untuk fitur manajemen user
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Routes untuk fitur manajemen proposal
        Route::get('/admin/proposals', [App\Http\Controllers\Admin\ProposalController::class, 'index'])->name('admin.proposals.index');
        Route::get('/admin/proposals/create', [App\Http\Controllers\Admin\ProposalController::class, 'create'])->name('admin.proposals.create');
        Route::post('/admin/proposals', [App\Http\Controllers\Admin\ProposalController::class, 'store'])->name('admin.proposals.store');
        Route::get('/admin/proposals/{proposal}/edit', [App\Http\Controllers\Admin\ProposalController::class, 'edit'])->name('admin.proposals.edit');
        Route::put('/admin/proposals/{proposal}', [App\Http\Controllers\Admin\ProposalController::class, 'update'])->name('admin.proposals.update');
        Route::delete('/admin/proposals/{proposal}', [App\Http\Controllers\Admin\ProposalController::class, 'destroy'])->name('admin.proposals.destroy');
    });

    Route::middleware(['auth', 'role:pemohon'])->group(function () {
        Route::get('/pemohon/dashboard', [PemohonController::class, 'dashboard'])->name('pemohon.dashboard');

        Route::get('/pemohon/proposals', [PengajuanSuratController::class, 'index'])->name('pemohon.proposals.index');
        Route::get('/pemohon/proposals/create', [PengajuanSuratController::class, 'create'])->name('pemohon.proposals.create');
        Route::post('/pemohon/proposals', [PengajuanSuratController::class, 'store'])->name('pemohon.proposals.store');
        Route::delete('/pemohon/proposals/{id}', [PengajuanSuratController::class, 'destroy'])->name('pemohon.proposals.destroy');
        Route::get('/pemohon/proposals/{id}', [PengajuanSuratController::class, 'show'])->name('pemohon.proposals.show');
    });

    Route::middleware(['auth', 'role:tu'])->group(function () {
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

        // Route untuk fitur Disposisi Proposal
        Route::get('/tu/disposisi-proposal', [DisposisiController::class, 'index'])->name('tu.disposisi.index');
        Route::get('/tu/disposisi-proposal/{proposal}/edit', [DisposisiController::class, 'edit'])->name('tu.disposisi.show');
        Route::put('/tu/disposisi-proposal/{proposal}', [DisposisiController::class, 'update'])->name('tu.disposisi.update');
        // Route untuk menyelesaikan proposal
        Route::get('/tu/disposisi-proposal/{id}/reject', [DisposisiController::class, 'rejectForm'])->name('tu.disposisi.reject');
        Route::put('tu/disposisi/reject/submit/{id}', [DisposisiController::class, 'rejectSubmit'])->name('tu.disposisi.reject.submit');
        Route::get('/tu/disposisi-proposal/{id}/selesaikan', [DisposisiController::class, 'selesaikan'])->name('tu.disposisi.selesaikan');

        // Route Arsip Surat
        Route::get('/tu/arsip-surat', [ArsipSuratController::class, 'index'])->name('tu.arsip.index');
        Route::delete('/tu/arsip-surat/{proposal}', [ArsipSuratController::class, 'destroy'])->name('tu.arsip.destroy');

        // Alasan Penolakan 
        // Route::get('proposals/{proposal}/reject', [PengajuanProposalController::class, 'rejectForm'])->name('tu.proposals.rejectForm');
        Route::put('proposals/{proposal}/reject', [PengajuanProposalController::class, 'reject'])->name('tu.proposals.reject');
    });

    Route::middleware(['auth', 'role:dekan'])->group(function () {
        // Dekan routes
        Route::get('/dekan/dashboard', [DekanController::class, 'dashboard'])->name('dekan.dashboard');

        Route::get('/dekan/disposisi', [DekanDisposisiController::class, 'index'])->name('disposisi.index');
        Route::get('/dekan/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/dekan/disposisi/{id}/edit', [DekanDisposisiController::class, 'edit'])->name('disposisi.edit');
        Route::put('/dekan/disposisi/{proposal}', [DekanDisposisiController::class, 'updateDisposisi'])->name('disposisi.updateDisposisi');
        Route::get('/dekan/disposisi/{id}/reject', [DekanDisposisiController::class, 'reject'])->name('disposisi.reject');
        Route::put('/dekan/disposisi/{proposal}/reject', [DekanDisposisiController::class, 'updateReject'])->name('disposisi.updateReject');
    });

    Route::middleware(['auth', 'role:keuangan'])->group(function () {
        // Keuangan routes
        Route::get('/keuangan/dashboard', [KeuanganController::class, 'dashboard'])->name('keuangan.dashboard');

        Route::get('/keuangan/disposisi', [KeuanganDisposisiController::class, 'index'])->name('keuangan.disposisi.index');
        Route::get('/keuangan/monitoring', [KeuanganMonitoringController::class, 'index'])->name('keuangan.monitoring.index');
        Route::get('/keuangan/disposisi/{id}/edit', [KeuanganDisposisiController::class, 'edit'])->name('keuangan.disposisi.edit');
        Route::put('/keuangan/disposisi/{proposal}', [KeuanganDisposisiController::class, 'updateDisposisi'])->name('keuangan.disposisi.updateDisposisi');
        Route::get('/keuangan/disposisi/{id}/reject', [KeuanganDisposisiController::class, 'reject'])->name('keuangan.disposisi.reject');
        Route::put('/keuangan/disposisi/{proposal}/reject', [KeuanganDisposisiController::class, 'updateReject'])->name('keuangan.disposisi.updateReject');
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

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
