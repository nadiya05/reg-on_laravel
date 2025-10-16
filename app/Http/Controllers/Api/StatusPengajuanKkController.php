<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKk;

class StatusPengajuanKkController extends Controller
{
    public function index(Request $request)
    {
        // Ambil user yang sedang login (dari token)
        $user = $request->user();

        // Filter data berdasarkan user_id
        $data = PengajuanKk::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_kk', 'status', 'tanggal_pengajuan')
            ->get();

        return response()->json($data);
    }

    public function resume($id, Request $request)
    {
        $user = $request->user();

        // Pastikan data hanya milik user yang login
        $data = PengajuanKk::where('user_id', $user->id)->findOrFail($id);

        return response()->json($data);
    }
}
