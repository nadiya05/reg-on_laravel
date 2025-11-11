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

        $gabungan = $ktp->merge($kk)->merge($kia)->sortByDesc('tanggal_pengajuan')->values();

        return response()->json([
            'success' => true,
            'data' => $gabungan,
        ]);
    }
}
