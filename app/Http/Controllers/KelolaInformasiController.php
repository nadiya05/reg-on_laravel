<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelolaInformasiController extends Controller
{
    // Tampilkan semua data (kosong dulu)
    public function index()
    {
        $informasi = []; // masih kosong
        return view('admin.kelola-informasi.index', compact('informasi'));
    }

    // Form tambah
    public function create()
    {
        return view('admin.kelola-informasi.create');
    }

    // Simpan data baru (sementara belum ada database)
    public function store(Request $request)
    {
        // validasi aja dulu
        $request->validate([
            'jenis_pengajuan' => 'required|string|max:255',
            'jenis_dokumen'   => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
        ]);

        // belum ada penyimpanan, langsung redirect
        return redirect()->route('admin.informasi.index')->with('success', 'Data berhasil ditambahkan (dummy)!');
    }

    // Form edit
    public function edit($id)
    {
        $info = null; // kosong karena belum ada data
        return view('admin.informasi.edit', compact('info'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pengajuan' => 'required|string|max:255',
            'jenis_dokumen'   => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
        ]);

        return redirect()->route('admin.informasi.index')->with('success', 'Data berhasil diperbarui (dummy)!');
    }

    // Hapus data
    public function destroy($id)
    {
        return redirect()->route('admin.informasi.index')->with('success', 'Data berhasil dihapus (dummy)!');
    }
}
