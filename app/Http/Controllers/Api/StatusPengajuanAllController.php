<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKTP;
use App\Models\PengajuanKK;
use App\Models\PengajuanKIA;

class StatusPengajuanAllController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 🔸 Ambil data dari masing-masing tabel
        $ktp = PengajuanKtp::where('user_id', $user->id)
            ->select('id', 'jenis_ktp as jenis_detail', 'status', 'tanggal_pengajuan')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis_pengajuan' => 'KTP',
                    'jenis_detail' => $item->jenis_detail,
                    'status' => $item->status,
                    'tanggal_pengajuan' => $item->tanggal_pengajuan,
                ];
            });

        $kk = PengajuanKk::where('user_id', $user->id)
            ->select('id', 'jenis_kk as jenis_detail', 'status', 'tanggal_pengajuan')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis_pengajuan' => 'KK',
                    'jenis_detail' => $item->jenis_detail,
                    'status' => $item->status,
                    'tanggal_pengajuan' => $item->tanggal_pengajuan,
                ];
            });

        $kia = PengajuanKia::where('user_id', $user->id)
            ->select('id', 'jenis_kia as jenis_detail', 'status', 'tanggal_pengajuan')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jenis_pengajuan' => 'KIA',
                    'jenis_detail' => $item->jenis_detail,
                    'status' => $item->status,
                    'tanggal_pengajuan' => $item->tanggal_pengajuan,
                ];
            });

        // 🔸 Gabungkan semua
        $gabungan = $ktp
            ->merge($kk)
            ->merge($kia)
            ->sortByDesc('tanggal_pengajuan')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $gabungan
        ]);
    }
}