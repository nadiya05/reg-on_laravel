<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKk;

class PengajuanKkController extends Controller
{
    // 🔹 Daftar jenis pengajuan KK
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 1, 'jenis' => 'Pemula'],
                ['id' => 2, 'jenis' => 'Ubah Status'],
            ]
        ]);
    }

    // 🔹 Simpan pengajuan KK Pemula
    public function storePemula(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'formulir_permohonan_kk' => 'required|file',
            'surat_nikah' => 'required|file',
            'surat_keterangan_pindah' => 'required|file',
        ]);

        $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
        $data['jenis_kk'] = 'Pemula';
        $data['user_id'] = auth()->id();

        // Simpan dokumen
        $data['formulir_permohonan_kk'] = $request->file('formulir_permohonan_kk')->store('kk/formulir', 'public');
        $data['surat_nikah'] = $request->file('surat_nikah')->store('kk/surat_nikah', 'public');
        $data['surat_keterangan_pindah'] = $request->file('surat_keterangan_pindah')->store('kk/surat_pindah', 'public');

        $kk = PengajuanKk::create($data);

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

    // 🔹 Simpan pengajuan KK Ubah Status
    public function storeUbahStatus(Request $request)
{
    $request->validate([
        'nik' => 'required|string',
        'nama' => 'required|string',
        'tanggal_pengajuan' => 'required|date',
        'kk_asli' => 'required|file',
        'surat_nikah' => 'required|file',
        'surat_kematian' => 'required|file',
        'surat_keterangan_pindah' => 'required|file',
    ]);

    $data = $request->only(['nik', 'nama', 'tanggal_pengajuan']);
    $data['jenis_kk'] = 'Ubah Status';
    $data['user_id'] = auth()->id();

    // Simpan semua dokumen wajib
    $data['kk_asli'] = $request->file('kk_asli')->store('kk/kk_asli', 'public');
    $data['surat_nikah'] = $request->file('surat_nikah')->store('kk/surat_nikah', 'public');
    $data['surat_kematian'] = $request->file('surat_kematian')->store('kk/surat_kematian', 'public');
    $data['surat_keterangan_pindah'] = $request->file('surat_keterangan_pindah')->store('kk/surat_pindah', 'public');

    $kk = PengajuanKk::create($data);

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
    // 🔹 Detail pengajuan KK
    public function show($id)
    {
        $kk = PengajuanKk::where('user_id', auth()->id())->find($id);

        if (!$kk) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_antrean' => str_pad($kk->nomor_antrean, 3, '0', STR_PAD_LEFT),
                'nik' => $kk->nik,
                'nama' => $kk->nama,
                'email' => $user->email,
                'no_hp' => $user->no_telp ?? '-',
                'jenis_kk' => $kk->jenis_kk,
                'tanggal_pengajuan' => $kk->tanggal_pengajuan,
            ]
        ]);
    }
}
