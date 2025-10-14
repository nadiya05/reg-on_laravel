<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiKkController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 🔹 Ambil semua data dengan jenis_dokumen = 'KIA'
            $informasi = Informasi::where('jenis_dokumen', 'KK')
                ->orderBy('created_at', 'desc')
                ->get(['jenis_pengajuan', 'jenis_dokumen', 'deskripsi'])
                ->map(function ($info) {
                    return [
                        'jenis_pengajuan' => $info->jenis_pengajuan ?? '-',
                        'jenis_dokumen'   => $info->jenis_dokumen ?? '-',
                        'deskripsi'       => $info->deskripsi ?? '',
                    ];
                });

            // 🔹 Kembalikan hasil dalam format JSON rapi
            return response()->json([
                'success' => true,
                'message' => $informasi->isEmpty()
                    ? 'Belum ada informasi KK yang tersedia.'
                    : 'Data informasi KK berhasil diambil.',
                'data' => $informasi,
            ], 200);
        } catch (\Exception $e) {
            // 🔹 Tangkap error agar tidak blank di Flutter
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
