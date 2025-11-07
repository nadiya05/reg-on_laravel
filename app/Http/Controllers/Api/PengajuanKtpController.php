<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanKtp;
use App\Models\Notifikasi;

class PengajuanKtpController extends Controller
{
    // 🔹 List jenis pengajuan
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 1, 'jenis' => 'Pemula'],
                ['id' => 2, 'jenis' => 'Kehilangan'],
                ['id' => 3, 'jenis' => 'Rusak atau Ubah Status'],
            ]
        ]);
    }

    // 🔹 Simpan pengajuan KTP Pemula
    public function storePemula(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|string',
                'nama' => 'required|string',
                'tanggal_pengajuan' => 'required|date',
                'kk' => 'required|file',
                'ijazah_skl' => 'required|file',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }

            $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
            $data['jenis_ktp'] = 'Pemula';
            $data['user_id'] = $user->id;

            // Upload file
            $data['kk'] = $request->file('kk')->store('ktp/kk', 'public');
            $data['ijazah_skl'] = $request->file('ijazah_skl')->store('ktp/ijazah', 'public');

            $ktp = PengajuanKtp::create($data);

            // Simpan notifikasi
            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => 'Pengajuan KTP Pemula',
                'pesan' => 'Pengajuan KTP Pemula Anda berhasil dikirim dan sedang diproses.',
                'tanggal' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan KTP Pemula berhasil disimpan',
                'data' => $ktp
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 🔹 Simpan pengajuan KTP Kehilangan
    public function storeKehilangan(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|string',
                'nama' => 'required|string',
                'tanggal_pengajuan' => 'required|date',
                'kk' => 'required|file',
                'surat_kehilangan' => 'required|file',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }

            $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
            $data['jenis_ktp'] = 'Kehilangan';
            $data['user_id'] = $user->id;

            $data['kk'] = $request->file('kk')->store('ktp/kk', 'public');
            $data['surat_kehilangan'] = $request->file('surat_kehilangan')->store('ktp/kehilangan', 'public');

            $ktp = PengajuanKtp::create($data);

            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => 'Pengajuan KTP Kehilangan',
                'pesan' => 'Pengajuan KTP Kehilangan Anda berhasil dikirim dan sedang diproses.',
                'tanggal' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan KTP Kehilangan berhasil disimpan',
                'data' => $ktp
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 🔹 Simpan pengajuan KTP Rusak / Ubah Status
    public function storeRusak(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|string',
                'nama' => 'required|string',
                'tanggal_pengajuan' => 'required|date',
                'kk' => 'required|file',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }

            $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
            $data['jenis_ktp'] = 'Rusak atau Ubah Status';
            $data['user_id'] = $user->id;

            $data['kk'] = $request->file('kk')->store('ktp/kk', 'public');

            $ktp = PengajuanKtp::create($data);

            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => 'Pengajuan KTP Rusak/Ubah Status',
                'pesan' => 'Pengajuan KTP Rusak/Ubah Status Anda berhasil dikirim dan sedang diproses.',
                'tanggal' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan KTP Rusak/Ubah Status berhasil disimpan',
                'data' => $ktp
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 🔹 Detail pengajuan berdasarkan ID
    public function show($id, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau token tidak valid.'
            ], 401);
        }

        $ktp = PengajuanKtp::where('user_id', $user->id)->find($id);

        if (!$ktp) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_antrean' => str_pad($ktp->nomor_antrean, 3, '0', STR_PAD_LEFT),
                'nik' => $ktp->nik,
                'nama' => $ktp->nama,
                'email' => $user->email,
                'no_telp' => $user->no_telp ?? '-',
                'jenis_ktp' => $ktp->jenis_ktp,
                'tanggal_pengajuan' => $ktp->tanggal_pengajuan,
            ]
        ]);
    }
}
