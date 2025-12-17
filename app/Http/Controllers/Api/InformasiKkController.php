<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;

class InformasiKkController extends Controller
{
    public function index()
    {
        try {
            $informasi = Informasi::where('jenis_dokumen', 'KK')
                ->orderBy('created_at', 'desc')
                ->get(['jenis_pengajuan', 'jenis_dokumen', 'deskripsi']);

            return response()->json([
                'success' => true,
                'message' => $informasi->isEmpty()
                    ? 'Belum ada informasi KK.'
                    : 'Data informasi KK berhasil diambil.',
                'data' => $informasi,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}