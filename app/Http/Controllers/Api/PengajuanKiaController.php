<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanKia;

class PengajuanKiaController extends Controller
{
    /**
     * 🔹 Ambil semua pengajuan KIA milik user login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $data = PengajuanKia::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'status', 'nomor_antrean', 'tanggal_pengajuan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * 🔹 Simpan pengajuan KIA baru (Pemula).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'kk' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'akta_lahir' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'surat_nikah' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'ktp_ortu' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'pass_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();
        $tanggal = $request->tanggal_pengajuan;

        // Nomor antrean berdasarkan tanggal pengajuan
        $last = PengajuanKia::whereDate('tanggal_pengajuan', $tanggal)
            ->orderBy('nomor_antrean', 'desc')
            ->first();

        $nextNumber = $last && is_numeric($last->nomor_antrean)
            ? str_pad(((int) $last->nomor_antrean) + 1, 3, "0", STR_PAD_LEFT)
            : "001";

        // Upload file jika ada
        $uploadPath = [];
        foreach (['kk', 'akta_lahir', 'surat_nikah', 'ktp_ortu', 'pass_foto'] as $file) {
            if ($request->hasFile($file)) {
                $uploadPath[$file] = $request->file($file)->store("pengajuan_kia/$file", 'public');
            }
        }

        // Simpan ke database
        $kia = PengajuanKia::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kia' => 'Pemula',
            'tanggal_pengajuan' => $tanggal,
            'nomor_antrean' => $nextNumber,
            'status' => 'Sedang Diproses',
            ...$uploadPath
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan KIA berhasil disimpan',
            'data' => [
                'id' => $kia->id,
                'nomor_antrean' => $kia->nomor_antrean,
                'jenis_kia' => $kia->jenis_kia,
                'nik' => $kia->nik,
                'nama' => $kia->nama,
                'tanggal_pengajuan' => $kia->tanggal_pengajuan,
            ]
        ], 201);
    }

    /**
     * 🔹 Tampilkan detail pengajuan KIA berdasarkan ID.
     */
    public function show($id, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        $kia = PengajuanKia::where('user_id', $user->id)->find($id);

        if (!$kia) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_antrean' => str_pad($kia->nomor_antrean, 3, '0', STR_PAD_LEFT),
                'nik' => $kia->nik,
                'nama' => $kia->nama,
                'email' => $user->email,
                'no_telp' => $user->no_telp ?? '-',
                'jenis_kia' => $kia->jenis_kia,
                'tanggal_pengajuan' => $kia->tanggal_pengajuan,
                'status' => $kia->status,
            ]
        ], 200);
    }

    /**
     * 🔹 Hapus pengajuan (jika masih Pending).
     */
    public function destroy($id)
    {
        $kia = PengajuanKia::where('user_id', auth()->id())->find($id);

        if (!$kia) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($kia->status !== 'Pending') {
            return response()->json(['message' => 'Data tidak dapat dihapus setelah diverifikasi'], 403);
        }

        // Hapus file yang diupload
        foreach (['kk', 'akta_lahir', 'surat_nikah', 'ktp_ortu', 'pass_foto'] as $file) {
            if ($kia->$file && Storage::exists('public/' . $kia->$file)) {
                Storage::delete('public/' . $kia->$file);
            }
        }

        $kia->delete();

        return response()->json(['message' => 'Pengajuan berhasil dihapus']);
    }
}
