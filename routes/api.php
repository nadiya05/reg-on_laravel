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
    Route::get('/pengajuan-kia/{id}', [PengajuanKiaController::class, 'show']);
    Route::delete('/pengajuan-kia/{id}', [PengajuanKiaController::class, 'destroy']);
     Route::get('/status_pengajuan_kia', [StatusPengajuanKiaController::class, 'index']);
    Route::get('/status_pengajuan_kia/{id}', [StatusPengajuanKiaController::class, 'resume']);
    Route::get('/informasi_kk', [InformasiKkController::class, 'index']);
});
