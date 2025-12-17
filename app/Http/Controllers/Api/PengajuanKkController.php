<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanKk;
use App\Models\Notifikasi;

class PengajuanKkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $data = PengajuanKk::where('user_id', $user->id)
            ->select(
                'id',
                'nik',
                'nama',
                'jenis_kk',
                'status',
                'nomor_antrean',
                'tanggal_pengajuan'
            )
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'jenis_kk' => 'required|string',
        'nik' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'tanggal_pengajuan' => 'required|date',
        'formulir_permohonan_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $user = $request->user();
    $jenis = $request->jenis_kk;

    $requiredFiles = match ($jenis) {
        'pendidikan' => ['ijazah'],
        'status_perkawinan' => ['surat_nikah'],     
        'perceraian'        => ['akta_cerai'],      
        'kematian'          => ['surat_kematian'],  
        'gol_darah' => ['bukti_cek_darah'],
        'penambahan_anggota' => ['akta_kelahiran'],
        'pindahan' => ['surat_keterangan_pindah'],
        'pisah_kk' => [],
        default => abort(422, 'Jenis KK tidak valid'),
    };

    foreach ($requiredFiles as $file) {
        if (!$request->hasFile($file)) {
            return response()->json([
                'success' => false,
                'message' => "Dokumen {$file} wajib diupload"
            ], 422);
        }
    }

    $last = PengajuanKk::whereDate('tanggal_pengajuan', $request->tanggal_pengajuan)
        ->orderByDesc('nomor_antrean')
        ->first();

    $nextNumber = $last
        ? str_pad(((int)$last->nomor_antrean) + 1, 3, '0', STR_PAD_LEFT)
        : '001';

    /** ================= UPLOAD FILE FIX ================= */
    $uploadPath = [];

    foreach ($request->allFiles() as $key => $file) {
        $filename = time() . '_' . $file->getClientOriginalName();

        $uploadPath[$key] = $file->storeAs(
            "pengajuan_kk/{$key}",
            $filename,
            'public'
        );
    }

    $kk = PengajuanKk::create([
        'user_id' => $user->id,
        'nik' => $request->nik,
        'nama' => $request->nama,
        'jenis_kk' => $jenis,
        'tanggal_pengajuan' => $request->tanggal_pengajuan,
        'nomor_antrean' => $nextNumber,
        'status' => 'Sedang Diproses',
        ...$uploadPath
    ]);

    Notifikasi::create([
        'user_id' => $user->id,
        'judul' => 'Pengajuan KK',
        'pesan' => "Pengajuan KK ({$jenis}) berhasil dikirim.",
        'tanggal' => now(),
        'tipe_pengajuan' => 'KK',
        'pengajuan_id' => $kk->id,
    ]);

    return response()->json([
        'success' => true,
        'data' => ['id' => $kk->id]
    ], 201);
}
    public function show($id, Request $request)
    {
        $user = $request->user();

        $kk = PengajuanKk::where('user_id', $user->id)->find($id);

        if (!$kk) {
            return response()->json(['success' => false], 404);
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
        ]);
    }

    public function destroy($id)
    {
        $kk = PengajuanKk::where('user_id', auth()->id())->find($id);

        if (!$kk) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($kk->status !== 'Sedang Diproses') {
            return response()->json(['message' => 'Tidak dapat dihapus'], 403);
        }

        foreach ($kk->getAttributes() as $key => $value) {
            if (
                str_contains($key, 'surat') ||
                str_contains($key, 'akta') ||
                str_contains($key, 'formulir')
            ) {
                if ($value) Storage::disk('public')->delete($value);
            }
        }

        $kk->delete();
        return response()->json(['message' => 'Pengajuan berhasil dihapus']);
    }
}
