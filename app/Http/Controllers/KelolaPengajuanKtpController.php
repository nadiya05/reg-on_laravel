<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKtp;
use Illuminate\Http\Request;
use App\Models\User;
use PDF; // alias dari barryvdh/laravel-dompdf

class KelolaPengajuanKtpController extends Controller
{
    /**
     * Tampilkan daftar pengajuan.
     */
    public function index(Request $request)
    {
        $query = PengajuanKtp::query();

        // fitur search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('jenis_ktp', 'like', "%$search%");
        }

        $pengajuan = $query->latest()->paginate(10);

        return view('admin.pengajuan-ktp.index', compact('pengajuan'));
    }

    /**
     * Form tambah data.
     */
        public function create()
    {
        $jenisKtp = PengajuanKtp::getJenisKtpOptions();
        return view('admin.pengajuan-ktp.create', compact('jenisKtp'));
    }
    /**
     * Simpan data baru.
     */
    public function store(Request $request)
{
    $rules = [
        'jenis_ktp' => 'required|string|max:50',
        'nik' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'tanggal_pengajuan' => 'required|date',
        'kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'ijazah_skl' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'surat_kehilangan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    switch($request->jenis_ktp) {
        case 'Pemula':
            $rules['kk'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            $rules['ijazah_skl'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            break;
        case 'Kehilangan':
            $rules['kk'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            $rules['surat_kehilangan'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            break;
        case 'Rusak atau Ubah Status':
            $rules['kk'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            break;
    }

    $request->validate($rules);

    $data = $request->all();
    $data['user_id'] = auth()->id();

    // generate nomor antrean per hari
    $today = now()->toDateString();
    $last = PengajuanKtp::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
    if ($last) {
        $lastNumber = intval($last->nomor_antrean);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }
    $data['nomor_antrean'] = $newNumber;

    // upload file kalau ada
    if ($request->hasFile('kk')) {
        $data['kk'] = $request->file('kk')->store('dokumen/kk', 'public');
    }
    if ($request->hasFile('ijazah_skl')) {
        $data['ijazah_skl'] = $request->file('ijazah_skl')->store('dokumen/ijazah', 'public');
    }
    if ($request->hasFile('surat_kehilangan')) {
        $data['surat_kehilangan'] = $request->file('surat_kehilangan')->store('dokumen/surat', 'public');
    }

    PengajuanKtp::create($data);

    return redirect()->route('admin.pengajuan-ktp.index')->with('success', 'Data berhasil ditambahkan dengan nomor antrean ' . $newNumber);
}
    /**
     * Form edit data.a
     */
        public function edit($id)
    {
        $pengajuan = PengajuanKtp::findOrFail($id);
        $jenisKtp = PengajuanKtp::getJenisKtpOptions(); // ambil enum dari database
        return view('admin.pengajuan-ktp.edit', compact('pengajuan', 'jenisKtp'));
    }
    /**
     * Update data.
     */
    public function update(Request $request, $id)
{
    $pengajuan = PengajuanKtp::findOrFail($id);

    // Aturan dasar
    $rules = [
        'jenis_ktp' => 'required|string|max:50',
        'nik' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'tanggal_pengajuan' => 'required|date',
        'kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'ijazah_skl' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'surat_kehilangan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    // Conditional rules berdasarkan jenis_ktp dan file yang sudah ada
    switch($request->jenis_ktp) {
        case 'Pemula':
            if (!$pengajuan->kk) {
                $rules['kk'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }
            if (!$pengajuan->ijazah_skl) {
                $rules['ijazah_skl'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }
            break;
        case 'Kehilangan':
            if (!$pengajuan->kk) {
                $rules['kk'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }
            if (!$pengajuan->surat_kehilangan) {
                $rules['surat_kehilangan'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }
            break;
        case 'Rusak atau Ubah Status':
            if (!$pengajuan->kk) {
                $rules['kk'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }
            // ijazah_skl dan surat_kehilangan optional
            break;
    }

    $request->validate($rules);

    $data = $request->all();
    $data['user_id'] = auth()->id(); // optional, kalau mau tetap update user

    // Upload file baru kalau ada
    if ($request->hasFile('kk')) {
        $data['kk'] = $request->file('kk')->store('dokumen/kk', 'public');
    }
    if ($request->hasFile('ijazah_skl')) {
        $data['ijazah_skl'] = $request->file('ijazah_skl')->store('dokumen/ijazah', 'public');
    }
    if ($request->hasFile('surat_kehilangan')) {
        $data['surat_kehilangan'] = $request->file('surat_kehilangan')->store('dokumen/surat', 'public');
    }

    $pengajuan->update($data);

    return redirect()->route('admin.pengajuan-ktp.index')->with('success', 'Data berhasil diupdate');
}
    /**
     * Hapus data.
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanKtp::findOrFail($id);

        // hapus file kalau ada
        if ($pengajuan->kk && \Storage::exists('public/' . $pengajuan->kk)) {
            \Storage::delete('public/' . $pengajuan->kk);
        }
        if ($pengajuan->ijazah_skl && \Storage::exists('public/' . $pengajuan->ijazah_skl)) {
            \Storage::delete('public/' . $pengajuan->ijazah_skl);
        }
        if ($pengajuan->surat_kehilangan && \Storage::exists('public/' . $pengajuan->surat_kehilangan)) {
            \Storage::delete('public/' . $pengajuan->surat_kehilangan);
        }

        $pengajuan->delete();

        return redirect()->route('admin.pengajuan-ktp.index')->with('success', 'Data berhasil dihapus');
    }
}
