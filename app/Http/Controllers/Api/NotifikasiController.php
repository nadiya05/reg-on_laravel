<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\PengajuanKtp;
use App\Models\PengajuanKk;
use App\Models\PengajuanKia;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->query('search');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        // Ambil semua notifikasi user
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

        // 🔍 Filtering termasuk nama_pengajuan
        if ($search) {
            $searchLower = strtolower($search);
            $notifikasi = $notifikasi->filter(function ($n) use ($searchLower) {
                return str_contains(strtolower($n['judul']), $searchLower)
                    || str_contains(strtolower($n['pesan']), $searchLower)
                    || str_contains(strtolower($n['tipe_pengajuan']), $searchLower)
                    || str_contains(strtolower($n['nama_pengajuan']), $searchLower);
            })->values();
        }

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    public function updateStatus($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['status' => 'dibaca']);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
        ]);
    }

    public function destroy($id)
    {
        Notifikasi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus',
        ]);
    }
}
