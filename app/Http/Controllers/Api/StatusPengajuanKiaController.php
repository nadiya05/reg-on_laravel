<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKia;

class StatusPengajuanKiaController extends Controller
{
    /**
     * 🔹 Ambil semua pengajuan KIA milik user yang sedang login
     */
    public function index(Request $request)
    {
        // Ambil user dari token Sanctum
        $user = $request->user();

        // Filter berdasarkan user_id
        $data = PengajuanKia::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_kia', 'status', 'tanggal_pengajuan')
            ->get();

        return response()->json($data);
    }

    /**
     * 🔹 Tampilkan detail satu pengajuan (resume)
     */
    public function resume($id, Request $request)
    {
        $user = $request->user();

        // Pastikan hanya data milik user yang login yang bisa diakses
        $data = PengajuanKia::where('user_id', $user->id)->findOrFail($id);

        return response()->json($data);
    }
}
