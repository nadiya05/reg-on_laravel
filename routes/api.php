<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\KelolaAkunController;
use App\Http\Controllers\Api\InformasiController;
use App\Http\Controllers\Api\PengajuanKtpController;
use App\Http\Controllers\Api\StatusPengajuanKtpController;
use App\Http\Controllers\Api\InformasiKiaController;
use App\Http\Controllers\Api\PengajuanKiaController;
use App\Http\Controllers\Api\StatusPengajuanKiaController;
use App\Http\Controllers\Api\InformasiKkController;
use App\Http\Controllers\Api\PengajuanKkController;
use App\Http\Controllers\Api\StatusPengajuanKkController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\StatusPengajuanAllController;



// Route untuk API Users
Route::apiResource('users', UserController::class);

// Route untuk Auth API
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pengajuan_ktp', [PengajuanKtpController::class, 'index']);
    Route::post('/pengajuan_ktp/pemula', [PengajuanKtpController::class, 'storePemula']);
    Route::post('/pengajuan_ktp/kehilangan', [PengajuanKtpController::class, 'storeKehilangan']);
    Route::post('/pengajuan_ktp/rusak', [PengajuanKtpController::class, 'storeRusak']);
    Route::put('/kelola-akun/{id}', [KelolaAkunController::class, 'updateApi']);
    Route::get('/informasi', [InformasiController::class, 'index']);
    Route::get('/pengajuan_ktp/{id}', [PengajuanKtpController::class, 'show']);
    Route::get('/status_pengajuan_ktp', [StatusPengajuanKtpController::class, 'index']);
    Route::get('/status_pengajuan_ktp/{id}', [StatusPengajuanKtpController::class, 'resume']);
    Route::get('/informasi_kia', [InformasiKiaController::class, 'index']);
    Route::get('/pengajuan-kia/pemula', [PengajuanKiaController::class, 'index']);
    Route::post('/pengajuan-kia', [PengajuanKiaController::class, 'store']);
    Route::get('/pengajuan_kia/{id}', [PengajuanKiaController::class, 'show']);
    Route::delete('/pengajuan-kia/{id}', [PengajuanKiaController::class, 'destroy']);
    Route::get('/status_pengajuan_kia', [StatusPengajuanKiaController::class, 'index']);
    Route::get('/status_pengajuan_kia/{id}', [StatusPengajuanKiaController::class, 'resume']);
    Route::get('/informasi_kk', [InformasiKkController::class, 'index']);
    Route::get('/pengajuan-kk', [PengajuanKkController::class, 'index']);
    Route::post('/pengajuan-kk/pemula', [PengajuanKkController::class, 'storePemula']);
    Route::post('/pengajuan-kk/ubah-status', [PengajuanKkController::class, 'storeUbahStatus']);
    Route::get('/pengajuan_kk/{id}', [PengajuanKkController::class, 'show']);
    Route::get('/status_pengajuan_kk', [StatusPengajuanKkController::class, 'index']);
    Route::get('/status_pengajuan_kk/{id}', [StatusPengajuanKkController::class, 'resume']);
    Route::get('/berita', [BeritaController::class, 'index']); // semua berita
    Route::get('/berita/{id}', [BeritaController::class, 'show']); // detail berita
    Route::get('/status_pengajuan_all', [StatusPengajuanAllController::class, 'index']);
});
