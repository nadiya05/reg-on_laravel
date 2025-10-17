<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKtp;

class StatusPengajuanKtpController extends Controller
{
    /**
     * 🔹 Ambil semua pengajuan KTP milik user yang sedang login
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Cegah error jika token tidak valid / user null
        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        // Ambil semua pengajuan milik user ini
        $data = PengajuanKtp::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_ktp', 'status', 'tanggal_pengajuan')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'total' => $data->count(),
            'data' => $data
        ], 200);
    }

    /**
     * 🔹 Tampilkan detail (resume) dari satu pengajuan KTP
     */
    public function resume($id, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        // Ambil data pengajuan milik user ini
        $data = PengajuanKtp::where('user_id', $user->id)
            ->select('*')
            ->find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Data pengajuan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
