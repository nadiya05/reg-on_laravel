<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // 🔹 Ambil semua notifikasi milik user login
    public function index(Request $request)
    {
        $user = $request->user();

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi,
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
