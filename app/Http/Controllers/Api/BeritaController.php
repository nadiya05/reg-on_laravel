<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // 🔹 Tampilkan semua berita
    public function index(Request $request)
    {
        try {
            $berita = Berita::latest()->get();

            if ($berita->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum ada berita tersedia',
                    'data' => []
                ]);
            }

            // Transform data supaya URL gambar lengkap
            $berita->transform(function ($item) {
                $item->foto = $item->foto
                    ? url('storage/' . $item->foto)  // pakai url() biar full path
                    : null;
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berita berhasil diambil',
                'data' => $berita
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 🔹 Detail berita
    public function show($id)
    {
        try {
            $berita = Berita::findOrFail($id);

            $berita->foto = $berita->foto
                ? url('storage/' . $berita->foto)
                : null;

            return response()->json([
                'success' => true,
                'message' => 'Detail berita ditemukan',
                'data' => $berita
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 404);
        }
    }
}
