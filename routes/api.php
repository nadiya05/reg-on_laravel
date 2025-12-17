<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;
use Pusher\Pusher;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserController;
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
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\ChatController;


// =======================================
// AUTH PUBLIC
// =======================================
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);


// =======================================
// BROADCASTING ROUTES (WAJIB DI LUAR AUTH GROUP)
// =======================================
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/pusher/auth', function (Request $request) {
    $pusher = new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        ['cluster' => config('broadcasting.connections.pusher.options.cluster')]
    );

    return $pusher->authenticate(
        $request->channel_name,
        $request->socket_id
    );
});


// =======================================
// AUTH SANCTUM (SEMUA BUTUH TOKEN LOGIN)
// =======================================
Route::middleware('auth:sanctum')->group(function () {

    // ==========================
    // CURRENT USER DATA (/me)
    // ==========================
    Route::get('/me', function (Request $request) {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'avatar' => $request->user()->foto
                ? asset('storage/'.$request->user()->foto)
                : asset('storage/default/user.png'),
        ]);
    });

    // USER API
    Route::apiResource('users', UserController::class);

    // --- KTP ---
    Route::get('/pengajuan_ktp', [PengajuanKtpController::class, 'index']);
    Route::post('/pengajuan_ktp/pemula', [PengajuanKtpController::class, 'storePemula']);
    Route::post('/pengajuan_ktp/kehilangan', [PengajuanKtpController::class, 'storeKehilangan']);
    Route::post('/pengajuan_ktp/rusak', [PengajuanKtpController::class, 'storeRusak']);
    Route::get('/pengajuan_ktp/{id}', [PengajuanKtpController::class, 'show']);
    Route::get('/status_pengajuan_ktp', [StatusPengajuanKtpController::class, 'index']);
    Route::get('/status_pengajuan_ktp/{id}', [StatusPengajuanKtpController::class, 'resume']);

    // --- KIA ---
    Route::get('/informasi_kia', [InformasiKiaController::class, 'index']);
    Route::get('/pengajuan-kia/pemula', [PengajuanKiaController::class, 'index']);
    Route::post('/pengajuan-kia', [PengajuanKiaController::class, 'store']);
    Route::get('/pengajuan_kia/{id}', [PengajuanKiaController::class, 'show']);
    Route::delete('/pengajuan-kia/{id}', [PengajuanKiaController::class, 'destroy']);
    Route::get('/status_pengajuan_kia', [StatusPengajuanKiaController::class, 'index']);
    Route::get('/status_pengajuan_kia/{id}', [StatusPengajuanKiaController::class, 'resume']);

    // --- KK ---
    Route::get('/informasi_kk', [InformasiKkController::class, 'index']);
    Route::get('/pengajuan-kk', [PengajuanKkController::class, 'index']);
    Route::post('/form-pengajuan-kk', [PengajuanKkController::class, 'store']);
    Route::get('/pengajuan-kk/{id}', [PengajuanKkController::class, 'show']);
    Route::get('/status_pengajuan_kk', [StatusPengajuanKkController::class, 'index']);
    Route::get('/status_pengajuan_kk/{id}', [StatusPengajuanKkController::class, 'resume']);

    // --- INFORMASI & BERITA ---
    Route::get('/informasi', [InformasiController::class, 'index']);
    Route::get('/berita', [BeritaController::class, 'index']); 
    Route::get('/berita/{id}', [BeritaController::class, 'show']); 
    Route::get('/status_pengajuan_all', [StatusPengajuanAllController::class, 'index']);

    // --- NOTIFIKASI ---
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::put('/notifikasi/{id}/baca', [NotifikasiController::class, 'updateStatus']);
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy']);

    // =======================================
    // CHAT SYSTEM API
    // =======================================
Route::middleware('auth:sanctum')->get('/chat/profile', [ChatController::class, 'profile']);
    Route::post('/chat/start', [ChatController::class, 'startChat']);
    Route::get('/messages', [ChatController::class, 'index']);
    Route::get('/messages/{userId}', [ChatController::class, 'show'])
        ->middleware('is_admin');
    Route::post('/messages', [ChatController::class, 'send']);
    Route::post('/messages/{userId}/reply', [ChatController::class, 'adminSend'])
        ->middleware('is_admin');

});
