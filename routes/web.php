<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaAkunController;
use App\Http\Controllers\KelolaInformasiController;
use App\Http\Controllers\KelolaPengajuanKtpController;
use App\Http\Controllers\KelolaStatusKtpController;
use App\Http\Controllers\KelolaPengajuanKiaController;
use App\Http\Controllers\KelolaStatusKiaController;

Route::get('/', function () { return view('auth.masuk'); });
// Halaman login
Route::get('/masuk', [AuthController::class, 'showLogin'])->name('masuk');

// Proses login
Route::post('/masuk', [AuthController::class, 'login'])->name('login.proses');

// Logout
Route::post('/keluar', [AuthController::class, 'logout'])->name('keluar');

// Beranda setelah login
Route::get('/beranda', function () {
    return view('admin.beranda');
})->name('beranda')->middleware('auth');

// Beranda admin
Route::get('/admin/beranda', [BerandaController::class, 'index'])->name('admin.beranda');

// Kelola Akun Pengguna
Route::get('/admin/kelola-akun', [KelolaAkunController::class, 'index'])->name('admin.kelola-akun');
Route::get('/admin/kelola-akun/create', [KelolaAkunController::class, 'create'])->name('admin.kelola-akun.create');
Route::post('/admin/kelola-akun/store', [KelolaAkunController::class, 'store'])->name('admin.kelola-akun.store');
Route::get('/admin/kelola-akun/{id}/edit', [KelolaAkunController::class, 'edit'])->name('admin.kelola-akun.edit');
Route::put('/admin/kelola-akun/{id}', [KelolaAkunController::class, 'update'])->name('admin.kelola-akun.update');
Route::delete('/admin/kelola-akun/{id}', [KelolaAkunController::class, 'destroy'])->name('admin.kelola-akun.destroy');

// Kelola Informasi
Route::get('/admin/kelola-informasi', [KelolaInformasiController::class, 'index'])->name('admin.kelola-informasi');
Route::get('/admin/kelola-informasi/create', [KelolaInformasiController::class, 'create'])->name('admin.kelola-informasi.create');
Route::post('/admin/kelola-informasi/store', [KelolaInformasiController::class, 'store'])->name('admin.kelola-informasi.store');
Route::get('/admin/kelola-informasi/{id}/edit', [KelolaInformasiController::class, 'edit'])->name('admin.kelola-informasi.edit');
Route::put('/admin/kelola-informasi/{id}', [KelolaInformasiController::class, 'update'])->name('admin.kelola-informasi.update');
Route::delete('/admin/kelola-informasi/{id}', [KelolaInformasiController::class, 'destroy'])->name('admin.kelola-informasi.destroy');

// Kelola Pengajuan KTP
Route::get('/admin/pengajuan-ktp', [KelolaPengajuanKtpController::class, 'index'])->name('admin.pengajuan-ktp.index');
Route::get('/admin/pengajuan-ktp/create', [KelolaPengajuanKtpController::class, 'create'])->name('admin.pengajuan-ktp.create');
Route::post('/admin/pengajuan-ktp/store', [KelolaPengajuanKtpController::class, 'store'])->name('admin.pengajuan-ktp.store');
Route::get('/admin/pengajuan-ktp/{id}/edit', [KelolaPengajuanKtpController::class, 'edit'])->name('admin.pengajuan-ktp.edit');
Route::put('/admin/pengajuan-ktp/{id}', [KelolaPengajuanKtpController::class, 'update'])->name('admin.pengajuan-ktp.update');
Route::delete('/admin/pengajuan-ktp/{id}', [KelolaPengajuanKtpController::class, 'destroy'])->name('admin.pengajuan-ktp.destroy');

// Resume
Route::get('resume_pengajuan/{id}', [KelolaStatusKtpController::class, 'resume'])->name('resume_pengajuan');
Route::get('cetak_resume/pdf/{id}', [KelolaStatusKtpController::class, 'cetakResumePdf'])->name('cetak_resume.pdf');

//Status-ktp
Route::get('/pengajuan-ktp/status', [KelolaStatusKtpController::class, 'index'])->name('pengajuan-ktp.status');
Route::post('/pengajuan-ktp/status/update/{id}', [KelolaStatusKtpController::class, 'updateStatus'])->name('pengajuan-ktp.status.update');
Route::delete('/pengajuan-ktp/status/delete/{id}', [KelolaStatusKtpController::class, 'destroy'])->name('pengajuan-ktp.status.destroy');

// Kelola Pengajuan KIA
Route::get('/pengajuan-kia', [KelolaPengajuanKiaController::class, 'index'])->name('pengajuan-kia.index');
Route::get('/pengajuan-kia/create', [KelolaPengajuanKiaController::class, 'create'])->name('pengajuan-kia.create');
Route::post('/pengajuan-kia/store', [KelolaPengajuanKiaController::class, 'store'])->name('pengajuan-kia.store');
Route::get('/pengajuan-kia/{id}/edit', [KelolaPengajuanKiaController::class, 'edit'])->name('pengajuan-kia.edit');
Route::put('/pengajuan-kia/{id}', [KelolaPengajuanKiaController::class, 'update'])->name('pengajuan-kia.update');
Route::delete('/pengajuan-kia/{id}', [KelolaPengajuanKiaController::class, 'destroy'])->name('pengajuan-kia.destroy');

//status-kia
Route::get('/status', [KelolaStatusKiaController::class, 'index'])->name('admin.pengajuan-kia.status');
Route::post('/status/update/{id}', [KelolaStatusKiaController::class, 'updateStatus'])->name('admin.pengajuan-kia.status.update');
Route::delete('/status/destroy/{id}', [KelolaStatusKiaController::class, 'destroy'])->name('admin.pengajuan-kia.status.destroy');
Route::get('resume_pengajuan/{id}', [KelolaStatusKiaController::class, 'resume'])->name('resume_pengajuan');
Route::get('cetak_resume/pdf/{id}', [KelolaStatusKiaController::class, 'cetakResumePdf'])->name('cetak_resume.pdf');



