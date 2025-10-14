<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class KelolaPengajuanKkController extends Controller
{
    /**
     * Tampilkan daftar pengajuan KK.
     */
    public function index(Request $request)
    {
        $query = PengajuanKk::query();

        // Fitur pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('jenis_kk', 'like', "%$search%");
        }

        $pengajuan = $query->latest()->paginate(10);

        return view('admin.pengajuan-kk.index', compact('pengajuan'));
    }

    /**
     * Form tambah pengajuan KK.
     */
    public function create()
    {
        $jenisKk = PengajuanKk::getJenisKkOptions();
        return view('admin.pengajuan-kk.create', compact('jenisKk'));
    }

    /**
     * Simpan data pengajuan KK.
     */
    public function store(Request $request)
    {
        $rules = [
            'jenis_kk' => 'required|string|max:50',
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'formulir_permohonan_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_nikah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_keterangan_pindah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kk_asli' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_kematian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'akta_kelahiran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ijazah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $request->validate($rules);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Generate nomor antrean per hari
        $today = now()->toDateString();
        $last = PengajuanKk::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = intval($last->nomor_antrean);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        $data['nomor_antrean'] = $newNumber;

        // Upload dokumen jika ada
        $fileFields = [
            'formulir_permohonan_kk' => 'dokumen/formulir_kk',
            'surat_nikah' => 'dokumen/nikah',
            'surat_keterangan_pindah' => 'dokumen/pindah',
            'kk_asli' => 'dokumen/kk_asli',
            'surat_kematian' => 'dokumen/kematian',
            'akta_kelahiran' => 'dokumen/akta',
            'ijazah' => 'dokumen/ijazah',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store($path, 'public');
            }
        }

        PengajuanKk::create($data);

        return redirect()->route('pengajuan-kk.index')->with('success', 'Data berhasil ditambahkan dengan nomor antrean ' . $newNumber);
    }

    /**
     * Form edit pengajuan KK.
     */
    public function edit($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $jenisKk = PengajuanKk::getJenisKkOptions();
        return view('admin.pengajuan-kk.edit', compact('pengajuan', 'jenisKk'));
    }

    /**
     * Update data pengajuan KK.
     */
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);

        $rules = [
            'jenis_kk' => 'required|string|max:50',
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'formulir_permohonan_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_nikah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_keterangan_pindah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kk_asli' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_kematian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'akta_kelahiran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ijazah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $request->validate($rules);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Upload dokumen baru jika ada
        $fileFields = [
            'formulir_permohonan_kk' => 'dokumen/formulir_kk',
            'surat_nikah' => 'dokumen/nikah',
            'surat_keterangan_pindah' => 'dokumen/pindah',
            'kk_asli' => 'dokumen/kk_asli',
            'surat_kematian' => 'dokumen/kematian',
            'akta_kelahiran' => 'dokumen/akta',
            'ijazah' => 'dokumen/ijazah',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store($path, 'public');
            }
        }

        $pengajuan->update($data);

        return redirect()->route('pengajuan-kk.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Hapus data pengajuan KK.
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);

        $fileFields = [
            'formulir_permohonan_kk',
            'surat_nikah',
            'surat_keterangan_pindah',
            'kk_asli',
            'surat_kematian',
            'akta_kelahiran',
            'ijazah',
        ];

        foreach ($fileFields as $field) {
            if ($pengajuan->$field && Storage::exists('public/' . $pengajuan->$field)) {
                Storage::delete('public/' . $pengajuan->$field);
            }
        }

        $pengajuan->delete();

        return redirect()->route('pengajuan-kk.index')->with('success', 'Data berhasil dihapus');
    }
}
