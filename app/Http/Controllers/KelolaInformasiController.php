<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;
use App\Models\PengajuanKtp;
use App\Models\PengajuanKk;
use App\Models\PengajuanKia;

class KelolaInformasiController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $query = Informasi::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_pengajuan', 'like', "%{$search}%")
                  ->orWhere('jenis_dokumen', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $informasi = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.kelola-informasi.index', compact('informasi'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.kelola-informasi.create', [
            'dokumenOptions' => ['KTP', 'KK', 'KIA'],
            'jenisPengajuanByDokumen' => [
                'KTP' => PengajuanKtp::getJenisKtpOptions(),
                'KK'  => PengajuanKk::getJenisKkOptions(),
                'KIA' => PengajuanKia::getJenisKiaOptions(),
            ]
        ]);
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'jenis_dokumen'   => 'required|in:KTP,KK,KIA',
            'jenis_pengajuan' => 'required|string',
            'deskripsi'       => 'nullable|string',
        ]);

        Informasi::create($request->only([
            'jenis_dokumen',
            'jenis_pengajuan',
            'deskripsi'
        ]));

        return redirect()
            ->route('admin.kelola-informasi')
            ->with('success', 'Data berhasil ditambahkan!');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $info = Informasi::findOrFail($id);

        return view('admin.kelola-informasi.edit', [
            'info' => $info,
            'dokumenOptions' => ['KTP', 'KK', 'KIA'],
            'jenisPengajuanByDokumen' => [
                'KTP' => PengajuanKtp::getJenisKtpOptions(),
                'KK'  => PengajuanKk::getJenisKkOptions(),
                'KIA' => PengajuanKia::getJenisKiaOptions(),
            ]
        ]);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_dokumen'   => 'required|in:KTP,KK,KIA',
            'jenis_pengajuan' => 'required|string',
            'deskripsi'       => 'nullable|string',
        ]);

        $info = Informasi::findOrFail($id);
        $info->update($request->only([
            'jenis_dokumen',
            'jenis_pengajuan',
            'deskripsi'
        ]));

        return redirect()
            ->route('admin.kelola-informasi')
            ->with('success', 'Data berhasil diperbarui!');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        Informasi::findOrFail($id)->delete();

        return redirect()
            ->route('admin.kelola-informasi')
            ->with('success', 'Data berhasil dihapus!');
    }
}
