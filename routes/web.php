<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaAkunController;
use App\Http\Controllers\KelolaInformasiController;

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

