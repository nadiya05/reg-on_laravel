<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth.masuk', function () {
    return view('auth.masuk');
})->name('masuk');

Route::get('/masuk', [AuthController::class, 'showLogin'])->name('masuk');
