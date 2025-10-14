<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data informasi yang jenis_dokumen-nya KTP, urutkan dari terbaru
        $informasi = Informasi::where('jenis_dokumen', 'KTP')
            ->orderBy('created_at', 'desc')
            ->get(['jenis_pengajuan', 'jenis_dokumen', 'deskripsi']) // ambil kolom yang dibutuhkan saja
            ->map(function ($info) {
                return [
                    'jenis_pengajuan' => $info->jenis_pengajuan ?? '-',
                    'jenis_dokumen'   => $info->jenis_dokumen ?? '-',
                    'deskripsi'       => $info->deskripsi ?? '',
                ];
            });

        // Jika tidak ada data, tetap kembalikan response JSON sukses tapi kosong
        return response()->json([
            'success' => true,
            'data' => $informasi,
        ]);
    }
}
