<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKtp;
use App\Models\PengajuanKk;
use App\Models\PengajuanKia;

class StatusPengajuanAllController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->query('search');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        // Ambil semua data sesuai user login
        $ktp = PengajuanKtp::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_ktp', 'status', 'tanggal_pengajuan')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'nik' => $item->nik,
                'nama' => $item->nama,
                'jenis_dokumen' => 'KTP',
                'jenis_pengajuan' => $item->jenis_ktp ?? '-',
                'status' => $item->status,
                'tanggal_pengajuan' => $item->tanggal_pengajuan,
            ]);

        $kk = PengajuanKk::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_kk', 'status', 'tanggal_pengajuan')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'nik' => $item->nik,
                'nama' => $item->nama,
                'jenis_dokumen' => 'KK',
                'jenis_pengajuan' => $item->jenis_kk ?? '-',
                'status' => $item->status,
                'tanggal_pengajuan' => $item->tanggal_pengajuan,
            ]);

        $kia = PengajuanKia::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'jenis_kia', 'status', 'tanggal_pengajuan')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'nik' => $item->nik,
                'nama' => $item->nama,
                'jenis_dokumen' => 'KIA',
                'jenis_pengajuan' => $item->jenis_kia ?? '-',
                'status' => $item->status,
                'tanggal_pengajuan' => $item->tanggal_pengajuan,
            ]);

        // Gabungkan semua data
        $gabungan = $ktp->merge($kk)->merge($kia)->sortByDesc('tanggal_pengajuan')->values();

        // 🔍 Sama seperti NotifikasiController: search di hasil gabungan
        if ($search) {
            $searchLower = strtolower($search);
            $gabungan = $gabungan->filter(function ($item) use ($searchLower) {
                return str_contains(strtolower($item['nik']), $searchLower)
                    || str_contains(strtolower($item['nama']), $searchLower)
                    || str_contains(strtolower($item['jenis_dokumen']), $searchLower)
                    || str_contains(strtolower($item['jenis_pengajuan']), $searchLower)
                    || str_contains(strtolower($item['status']), $searchLower)
                    || str_contains(strtolower($item['tanggal_pengajuan']), $searchLower);
            })->values();
        }

        return response()->json([
            'success' => true,
            'data' => $gabungan,
        ]);
    }
}