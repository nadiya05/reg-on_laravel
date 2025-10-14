<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKia;
use Illuminate\Http\Request;
use App\Models\User;

class KelolaPengajuanKiaController extends Controller
{
    /**
     * Tampilkan daftar pengajuan.
     */
    public function index(Request $request)
    {
        $query = PengajuanKia::query();

        // fitur search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('jenis_kia', 'like', "%$search%");
        }

        $pengajuan = $query->latest()->paginate(10);

        return view('admin.pengajuan-kia.index', compact('pengajuan'));
    }

    /**
     * Form tambah data.
     */
    public function create()
    {
        $jenisKia = PengajuanKia::getJenisKiaOptions();
        return view('admin.pengajuan-kia.create', compact('jenisKia'));
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'jenis_kia' => 'required|string|max:50',
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'akta_lahir' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_nikah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ktp_ortu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pass_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // KIA biasanya cuma 1 jenis ("pemula"), tapi tetap bisa dikembangkan nanti
        $request->validate($rules);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // generate nomor antrean per hari
        $today = now()->toDateString();
        $last = PengajuanKia::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
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
        if ($request->hasFile('akta_lahir')) {
            $data['akta_lahir'] = $request->file('akta_lahir')->store('dokumen/akta', 'public');
        }
        if ($request->hasFile('surat_nikah')) {
            $data['surat_nikah'] = $request->file('surat_nikah')->store('dokumen/nikah', 'public');
        }
        if ($request->hasFile('ktp_ortu')) {
            $data['ktp_ortu'] = $request->file('ktp_ortu')->store('dokumen/ktp_ortu', 'public');
        }
        if ($request->hasFile('pass_foto')) {
            $data['pass_foto'] = $request->file('pass_foto')->store('dokumen/foto', 'public');
        }

        PengajuanKia::create($data);

        return redirect()->route('pengajuan-kia.index')->with('success', 'Data berhasil ditambahkan dengan nomor antrean ' . $newNumber);
    }

    /**
     * Form edit data.
     */
    public function edit($id)
    {
        $pengajuan = PengajuanKia::findOrFail($id);
        $jenisKia = PengajuanKia::getJenisKiaOptions();
        return view('admin.pengajuan-kia.edit', compact('pengajuan', 'jenisKia'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanKia::findOrFail($id);

        $rules = [
            'jenis_kia' => 'required|string|max:50',
            'nik' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'akta_lahir' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'surat_nikah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ktp_ortu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pass_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $request->validate($rules);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // upload file baru kalau ada
        if ($request->hasFile('kk')) {
            $data['kk'] = $request->file('kk')->store('dokumen/kk', 'public');
        }
        if ($request->hasFile('akta_lahir')) {
            $data['akta_lahir'] = $request->file('akta_lahir')->store('dokumen/akta', 'public');
        }
        if ($request->hasFile('surat_nikah')) {
            $data['surat_nikah'] = $request->file('surat_nikah')->store('dokumen/nikah', 'public');
        }
        if ($request->hasFile('ktp_ortu')) {
            $data['ktp_ortu'] = $request->file('ktp_ortu')->store('dokumen/ktp_ortu', 'public');
        }
        if ($request->hasFile('pass_foto')) {
            $data['pass_foto'] = $request->file('pass_foto')->store('dokumen/foto', 'public');
        }

        $pengajuan->update($data);

        return redirect()->route('pengajuan-kia.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Hapus data.
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanKia::findOrFail($id);

        // hapus file kalau ada
        $files = ['kk', 'akta_lahir', 'surat_nikah', 'ktp_ortu', 'pass_foto'];
        foreach ($files as $file) {
            if ($pengajuan->$file && \Storage::exists('public/' . $pengajuan->$file)) {
                \Storage::delete('public/' . $pengajuan->$file);
            }
        }

        $pengajuan->delete();

        return redirect()->route('pengajuan-kia.index')->with('success', 'Data berhasil dihapus');
    }
}
