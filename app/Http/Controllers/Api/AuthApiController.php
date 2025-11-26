<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    // Registrasi penduduk
    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:users|max:16',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'no_telp' => 'required|string',
            'jenis_kelamin' => 'required|string',
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'role' => 'user', 
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 200);
    }

    // Login penduduk
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|max:16',
            'password' => 'required'
        ]);

        $user = User::where('nik', $request->nik)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'NIK atau password salah'
            ], 401);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;
        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token 
        ]);
    }
}
