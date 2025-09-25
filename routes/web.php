<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaAkunController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth.masuk', function () {
    return view('auth.masuk');
})->name('masuk');

Route::get('/masuk', [AuthController::class, 'showLogin'])->name('masuk');

// Beranda
Route::get('/admin/beranda', [BerandaController::class, 'index'])->name('admin.beranda');

//Kelola Akun Pengguna
Route::get('/admin/kelola-akun', [KelolaAkunController::class, 'index'])->name('admin.kelola-akun');
Route::get('/admin/kelola-akun/create', [KelolaAkunController::class, 'create'])->name('admin.kelola-akun.create');
Route::post('/admin/kelola-akun/store', [KelolaAkunController::class, 'store'])->name('admin.kelola-akun.store');
Route::get('/admin/kelola-akun/{id}/edit', [KelolaAkunController::class, 'edit'])->name('admin.kelola-akun.edit');
Route::put('/admin/kelola-akun/{id}', [KelolaAkunController::class, 'update'])->name('admin.kelola-akun.update');
Route::delete('/admin/kelola-akun/{id}', [KelolaAkunController::class, 'destroy'])->name('admin.kelola-akun.destroy');