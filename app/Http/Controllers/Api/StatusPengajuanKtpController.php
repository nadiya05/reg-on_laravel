<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKtp;

class StatusPengajuanKtpController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $search = strtolower($request->query('search', ''));

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        $query = PengajuanKtp::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_ktp', 'status', 'keterangan', 'tanggal_pengajuan')
            ->orderBy('tanggal_pengajuan', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nik) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(nama) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(jenis_ktp) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(status) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(tanggal_pengajuan) LIKE ?', ["%$search%"]);
            });
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'total' => $data->count(),
            'data' => $data,
        ]);
    }

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
            'success' => true,
            'data' => $data
        ]);
    }
}
