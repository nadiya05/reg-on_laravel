<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\KelolaAkunController;

// Route untuk API Users
Route::apiResource('users', UserController::class);

// Route untuk Auth API
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

//Route untuk simpan edit akun
// routes/api.php
Route::put('/kelola-akun/{id}', [KelolaAkunController::class, 'updateApi']);

