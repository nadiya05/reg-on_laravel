<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKk;

class StatusPengajuanKkController extends Controller
{
    /**
     * 🔹 Ambil semua pengajuan KK milik user yang sedang login
     * + 🔍 fitur search seperti KIA
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $search = strtolower($request->query('search', ''));

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        $query = PengajuanKk::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_kk', 'status', 'keterangan', 'tanggal_pengajuan')
            ->orderBy('tanggal_pengajuan', 'desc');

        // 🔍 Tambahkan filter pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nik) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(nama) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(jenis_kk) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(status) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(tanggal_pengajuan) LIKE ?', ["%$search%"]);
            });
        }

        $data = $query->get();

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

        $data = PengajuanKk::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_kk', 'status', 'keterangan', 'tanggal_pengajuan')
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
