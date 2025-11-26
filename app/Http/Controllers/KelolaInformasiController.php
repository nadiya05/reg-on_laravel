<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class KelolaInformasiController extends Controller
{
    // Tampilkan semua data
   public function index(Request $request)
{
    $query = Informasi::query();

    // fitur search (kaya di pengajuan KTP)
    if (!empty($request->search)) {
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

    public function create()
    {
        $pengajuanOptions = ['Pemula', 'Kehilangan', 'Rusak dan Ubah Status'];
        $dokumenOptions   = ['KTP', 'KK', 'KIA'];

        return view('admin.kelola-informasi.create', compact('pengajuanOptions', 'dokumenOptions'));
    }

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

    public function edit($id)
    {
        $info = Informasi::findOrFail($id);
        $pengajuanOptions = ['Pemula', 'Kehilangan', 'Rusak dan Ubah Status'];
        $dokumenOptions   = ['KTP', 'KK', 'KIA'];

        return view('admin.kelola-informasi.edit', compact('info', 'pengajuanOptions', 'dokumenOptions'));
    }

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

    public function destroy($id)
    {
        $info = Informasi::findOrFail($id);
        $info->delete();

        return redirect()->route('admin.kelola-informasi')->with('success', 'Data berhasil dihapus!');
    }
}
