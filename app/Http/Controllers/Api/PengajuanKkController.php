<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanKk;

class PengajuanKkController extends Controller
{
    /**
     * 🔹 Ambil semua pengajuan KK milik user login
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $data = PengajuanKk::where('user_id', $user->id)
            ->select('id', 'nik', 'nama', 'status', 'nomor_antrean', 'tanggal_pengajuan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * 🔹 Simpan pengajuan KK Pemula
     */
    public function storePemula(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'formulir_permohonan_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_nikah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_pindah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = $request->user();
        $tanggal = $request->tanggal_pengajuan;

        // Nomor antrean berdasarkan tanggal
        $last = PengajuanKk::whereDate('tanggal_pengajuan', $tanggal)
            ->orderBy('nomor_antrean', 'desc')
            ->first();

        $nextNumber = $last && is_numeric($last->nomor_antrean)
            ? str_pad(((int) $last->nomor_antrean) + 1, 3, "0", STR_PAD_LEFT)
            : "001";

        // Upload file
        $uploadPath = [];
        foreach (['formulir_permohonan_kk', 'surat_nikah', 'surat_keterangan_pindah'] as $file) {
            if ($request->hasFile($file)) {
                $uploadPath[$file] = $request->file($file)->store("pengajuan_kk/$file", 'public');
            }
        }

        $kk = PengajuanKk::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kk' => 'Pemula',
            'tanggal_pengajuan' => $tanggal,
            'nomor_antrean' => $nextNumber,
            'status' => 'Sedang Diproses',
            ...$uploadPath
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan KK Pemula berhasil disimpan',
            'data' => [
                'id' => $kk->id,
                'nomor_antrean' => $kk->nomor_antrean,
                'jenis_kk' => $kk->jenis_kk,
                'nik' => $kk->nik,
                'nama' => $kk->nama,
                'tanggal_pengajuan' => $kk->tanggal_pengajuan,
            ]
        ], 201);
    }

    /**
     * 🔹 Simpan pengajuan KK Ubah Status
     */
    public function storeUbahStatus(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'kk_asli' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_nikah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_kematian' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_pindah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = $request->user();
        $tanggal = $request->tanggal_pengajuan;

        // Nomor antrean
        $last = PengajuanKk::whereDate('tanggal_pengajuan', $tanggal)
            ->orderBy('nomor_antrean', 'desc')
            ->first();

        $nextNumber = $last && is_numeric($last->nomor_antrean)
            ? str_pad(((int) $last->nomor_antrean) + 1, 3, "0", STR_PAD_LEFT)
            : "001";

        $uploadPath = [];
        foreach (['kk_asli', 'surat_nikah', 'surat_kematian', 'surat_keterangan_pindah'] as $file) {
            if ($request->hasFile($file)) {
                $uploadPath[$file] = $request->file($file)->store("pengajuan_kk/$file", 'public');
            }
        }

        $kk = PengajuanKk::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kk' => 'Ubah Status',
            'tanggal_pengajuan' => $tanggal,
            'nomor_antrean' => $nextNumber,
            'status' => 'Sedang Diproses',
            ...$uploadPath
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan KK Ubah Status berhasil disimpan',
            'data' => [
                'id' => $kk->id,
                'nomor_antrean' => $kk->nomor_antrean,
                'jenis_kk' => $kk->jenis_kk,
                'nik' => $kk->nik,
                'nama' => $kk->nama,
                'tanggal_pengajuan' => $kk->tanggal_pengajuan,
            ]
        ], 201);
    }

    /**
     * 🔹 Detail pengajuan KK
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

        $kk = PengajuanKk::where('user_id', $user->id)->find($id);

        if (!$kk) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_antrean' => str_pad($kk->nomor_antrean, 3, '0', STR_PAD_LEFT),
                'nik' => $kk->nik,
                'nama' => $kk->nama,
                'email' => $user->email,
                'no_telp' => $user->no_telp ?? '-',
                'jenis_kk' => $kk->jenis_kk,
                'tanggal_pengajuan' => $kk->tanggal_pengajuan,
                'status' => $kk->status,
            ]
        ], 200);
    }

    /**
     * 🔹 Hapus pengajuan (jika masih Pending)
     */
    public function destroy($id)
    {
        $kk = PengajuanKk::where('user_id', auth()->id())->find($id);

        if (!$kk) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($kk->status !== 'Pending') {
            return response()->json(['message' => 'Data tidak dapat dihapus setelah diverifikasi'], 403);
        }

        foreach (['formulir_permohonan_kk', 'surat_nikah', 'surat_kematian', 'surat_keterangan_pindah', 'kk_asli'] as $file) {
            if ($kk->$file && Storage::exists('public/' . $kk->$file)) {
                Storage::delete('public/' . $kk->$file);
            }
        }

        $kk->delete();

        return response()->json(['message' => 'Pengajuan berhasil dihapus']);
    }
}
