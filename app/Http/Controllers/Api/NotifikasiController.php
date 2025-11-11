<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\PengajuanKtp;
use App\Models\PengajuanKk;
use App\Models\PengajuanKia;

class NotifikasiController extends Controller
{
    // 🔹 Ambil semua notifikasi milik user login
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notif) {
                $nama = '-';

                switch (strtoupper($notif->tipe_pengajuan)) {
                    case 'KTP':
                        $data = PengajuanKtp::find($notif->pengajuan_id);
                        $nama = $data->nama ?? '-';
                        break;

                    case 'KK':
                        $data = PengajuanKk::find($notif->pengajuan_id);
                        $nama = $data->nama ?? '-';
                        break;

                    case 'KIA':
                        $data = PengajuanKia::find($notif->pengajuan_id);
                        $nama = $data->nama ?? '-';
                        break;
                }

                return [
                    'id' => $notif->id,
                    'judul' => $notif->judul,
                    'pesan' => $notif->pesan,
                    'tanggal' => $notif->tanggal,
                    'status' => $notif->status,
                    'tipe_pengajuan' => $notif->tipe_pengajuan,
                    'nama_pengajuan' => $nama,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    // 🔹 Tandai sebagai dibaca
    public function updateStatus($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['status' => 'dibaca']);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
        ]);
    }

    // 🔹 Hapus notifikasi
    public function destroy($id)
    {
        Notifikasi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus',
        ]);
    }
}
