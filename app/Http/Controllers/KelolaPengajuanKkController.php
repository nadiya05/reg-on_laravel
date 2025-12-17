<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KelolaPengajuanKkController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanKk::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('jenis_kk', 'like', "%{$search}%")
                  ->orWhere('nomor_antrean', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->orderBy('tanggal_pengajuan', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        return view('admin.pengajuan-kk.index', compact('pengajuan'));
    }

    public function create()
    {
        $jenisKk = PengajuanKk::getJenisKkOptions();
        return view('admin.pengajuan-kk.create', compact('jenisKk'));
    }

    public function store(Request $request)
    {
        $rules = [
            'jenis_kk' => 'required|string',
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'formulir_permohonan_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_nikah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'akta_cerai' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_kematian' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_pindah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_cek_darah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_pisah_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
        $request->validate($rules);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Nomor antrean
        $today = now()->toDateString();
        $last = PengajuanKk::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
        $data['nomor_antrean'] = $last ? str_pad(intval($last->nomor_antrean) + 1, 3, '0', STR_PAD_LEFT) : '001';

        // Upload file
        $fileFields = [
            'formulir_permohonan_kk',
            'ijazah',
            'surat_nikah',
            'akta_cerai',
            'surat_kematian',
            'akta_kelahiran',
            'surat_keterangan_pindah',
            'bukti_cek_darah',
            'surat_pisah_kk',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("pengajuan_kk/{$field}", 'public');
            }
        }

        PengajuanKk::create($data);

        return redirect()->route('pengajuan-kk.index')
            ->with('success', 'Pengajuan berhasil dibuat dengan nomor antrean ' . $data['nomor_antrean']);
    }

    public function edit($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $jenisKk = PengajuanKk::getJenisKkOptions();
        return view('admin.pengajuan-kk.edit', compact('pengajuan', 'jenisKk'));
    }

    public function update(Request $request, $id)
{
    $pengajuan = PengajuanKk::findOrFail($id);

    $rules = [
        'jenis_kk' => 'required|string',
        'nik' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'tanggal_pengajuan' => 'required|date',
        'formulir_permohonan_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'surat_nikah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_cerai' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'surat_kematian' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'surat_keterangan_pindah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'bukti_cek_darah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'surat_pisah_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'surat_penggabungan_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];
    $request->validate($rules);

    $data = $request->except('user_id');

    // ===================== HAPUS DOKUMEN LAMA JIKA JENIS BERUBAH =====================
    $jenisLama = $pengajuan->jenis_kk;
    $jenisBaru = $request->jenis_kk;

    $dokumenByJenis = [
        'pendidikan' => ['ijazah'],
        'status_perkawinan' => ['surat_nikah'],
        'perceraian' => ['akta_cerai'],
        'kematian' => ['surat_kematian'],
        'gol_darah' => ['bukti_cek_darah'],
        'penambahan_anggota' => ['akta_kelahiran'],
        'pindahan' => ['surat_keterangan_pindah'],
        'pisah_kk' => ['surat_pisah_kk'],
        'penggabungan_kk' => ['surat_penggabungan_kk'],
    ];

    if ($jenisBaru !== $jenisLama && isset($dokumenByJenis[$jenisLama])) {
        foreach ($dokumenByJenis[$jenisLama] as $file) {
            if ($pengajuan->$file) {
                Storage::disk('public')->delete($pengajuan->$file);
                $data[$file] = null; // set null biar diupdate di db
            }
        }
    }

    // ===================== UPLOAD FILE BARU =====================
    $fileFields = [
        'formulir_permohonan_kk',
        'ijazah',
        'surat_nikah',
        'akta_cerai',
        'surat_kematian',
        'akta_kelahiran',
        'surat_keterangan_pindah',
        'bukti_cek_darah',
        'surat_pisah_kk',
        'surat_penggabungan_kk',
    ];

    foreach ($fileFields as $field) {
        if ($request->hasFile($field)) {
            if ($pengajuan->$field) Storage::disk('public')->delete($pengajuan->$field);
            $data[$field] = $request->file($field)->store("pengajuan_kk/{$field}", 'public');
        }
    }

    $pengajuan->update($data);

    return redirect()->route('pengajuan-kk.index')->with('success', 'Data berhasil diupdate');
}

    public function destroy($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);

        $fileFields = [
            'formulir_permohonan_kk',
            'ijazah',
            'surat_nikah',
            'akta_cerai',
            'surat_kematian',
            'akta_kelahiran',
            'surat_keterangan_pindah',
            'bukti_cek_darah',
            'surat_pisah_kk',
        ];

        foreach ($fileFields as $field) {
            if ($pengajuan->$field) Storage::disk('public')->delete($pengajuan->$field);
        }

        $pengajuan->delete();

        return redirect()->route('pengajuan-kk.index')->with('success', 'Data berhasil dihapus');
    }
}
