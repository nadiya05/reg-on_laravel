<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class KelolaInformasiController extends Controller
{
    // Tampilkan semua data
    public function index()
    {
        $informasi = Informasi::all();
        return view('admin.kelola-informasi.index', compact('informasi'));
    }

    // Form tambah
    public function create()
    {
        // opsi dropdown bisa kamu definisikan di sini
        $pengajuanOptions = ['Pemula', 'Kehilangan', 'Rusak dan Ubah Status'];
        $dokumenOptions   = ['KTP', 'KK', 'KIA'];

        return view('admin.kelola-informasi.create', compact('pengajuanOptions', 'dokumenOptions'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengajuan' => 'required|string|max:255',
            'jenis_dokumen'   => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
        ]);

        Informasi::create($request->all());

        return redirect()->route('admin.kelola-informasi')->with('success', 'Data berhasil ditambahkan!');
    }

    // Form edit
    public function edit($id)
    {
        $info = Informasi::findOrFail($id);
        $pengajuanOptions = ['Pemula', 'Kehilangan', 'Rusak dan Ubah Status'];
        $dokumenOptions   = ['KTP', 'KK', 'KIA'];

        return view('admin.kelola-informasi.edit', compact('info', 'pengajuanOptions', 'dokumenOptions'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pengajuan' => 'required|string|max:255',
            'jenis_dokumen'   => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
        ]);

        $info = Informasi::findOrFail($id);
        $info->update($request->all());

        return redirect()->route('admin.kelola-informasi')->with('success', 'Data berhasil diperbarui!');
    }

    // Hapus data
    public function destroy($id)
    {
        $info = Informasi::findOrFail($id);
        $info->delete();

        return redirect()->route('admin.kelola-informasi')->with('success', 'Data berhasil dihapus!');
    }
}
