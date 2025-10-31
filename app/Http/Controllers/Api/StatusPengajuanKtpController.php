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

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        $data = PengajuanKtp::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_ktp', 'status', 'keterangan', 'tanggal_pengajuan')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'total' => $data->count(),
            'data' => $data
        ], 200);
    }

    /**
     * 🔹 Detail pengajuan (resume)
     */
    public function resume($id, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        $data = PengajuanKtp::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_ktp', 'status', 'keterangan', 'tanggal_pengajuan')
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
