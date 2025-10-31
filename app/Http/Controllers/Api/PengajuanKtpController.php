<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanKtp;

class PengajuanKtpController extends Controller
{
    // List jenis pengajuan
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
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'kk' => 'required|file',
            'ijazah_skl' => 'required|file',
        ]);

        $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
        $data['jenis_ktp'] = 'Pemula';
        $data['user_id'] = auth()->id();

        $data['kk'] = $request->file('kk')->store('ktp/kk', 'public');
        $data['ijazah_skl'] = $request->file('ijazah_skl')->store('ktp/ijazah', 'public');

        $ktp = PengajuanKtp::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan Pemula berhasil disimpan',
            'data' => [
                'id' => $ktp->id,
                'nomor_antrean' => $ktp->nomor_antrean, // 🔑 nomor antrean ikut tampil
                'jenis_ktp' => $ktp->jenis_ktp,
                'nik' => $ktp->nik,
                'nama' => $ktp->nama,
                'tanggal_pengajuan' => $ktp->tanggal_pengajuan,
            ]
        ], 201);
    }

    // 🔹 Simpan pengajuan KTP Kehilangan
    public function storeKehilangan(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'kk' => 'required|file',
            'surat_kehilangan' => 'required|file',
        ]);

        $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
        $data['jenis_ktp'] = 'Kehilangan';
        $data['user_id'] = auth()->id();

        $data['kk'] = $request->file('kk')->store('ktp/kk', 'public');
        $data['surat_kehilangan'] = $request->file('surat_kehilangan')->store('ktp/kehilangan', 'public');

        $ktp = PengajuanKtp::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan Kehilangan berhasil disimpan',
            'data' => [
                'id' => $ktp->id,
                'nomor_antrean' => $ktp->nomor_antrean,
                'jenis_ktp' => $ktp->jenis_ktp,
                'nik' => $ktp->nik,
                'nama' => $ktp->nama,
                'tanggal_pengajuan' => $ktp->tanggal_pengajuan,
            ]
        ], 201);
    }

    // 🔹 Simpan pengajuan KTP Rusak / Ubah Status
    public function storeRusak(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'kk' => 'required|file',
        ]);

        $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
        $data['jenis_ktp'] = 'Rusak atau Ubah Status';
        $data['user_id'] = auth()->id();

        $data['kk'] = $request->file('kk')->store('ktp/kk', 'public');

        $ktp = PengajuanKtp::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan Rusak/Ubah Status berhasil disimpan',
            'data' => [
                'id' => $ktp->id,
                'nomor_antrean' => $ktp->nomor_antrean,
                'jenis_ktp' => $ktp->jenis_ktp,
                'nik' => $ktp->nik,
                'nama' => $ktp->nama,
                'tanggal_pengajuan' => $ktp->tanggal_pengajuan,
            ]
        ], 201);
    }

    // 🔹 Tampilkan data pengajuan KTP berdasarkan ID
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
        ], 200);
    }
}