<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelolaBeritaController extends Controller
{
    public function index(Request $request)
    {
        // Data-level refactoring: $search → $searchBerita
        $searchBerita = $request->input('search');  

        $berita = Berita::when($searchBerita, function ($query, $searchBerita) {
                return $query->where('judul', 'like', "%{$searchBerita}%");
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(5);

        return view('admin.kelola-berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.kelola-berita.create');
    }

    public function store(Request $request)
    {
        $this->validateBerita($request);

        $fotoPath = $this->uploadFoto($request);

        Berita::create([
            'judul'   => $request->judul,
            'tanggal' => $request->tanggal,
            'foto'    => $fotoPath,
            'konten'  => $request->konten,
        ]);

        return redirect()->route('admin.kelola-berita.index')
                         ->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.kelola-berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $this->validateBerita($request);

        $berita = Berita::findOrFail($id);

        if ($request->hasFile('foto')) {
            $this->hapusFotoLama($berita->foto);
            $berita->foto = $this->uploadFoto($request);
        }

        $berita->judul   = $request->judul;
        $berita->tanggal = $request->tanggal;
        $berita->konten  = $request->konten;
        $berita->save();

        return redirect()->route('admin.kelola-berita.index')
                         ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        $this->hapusFotoLama($berita->foto);

        $berita->delete();

        return redirect()->route('admin.kelola-berita.index')
                         ->with('success', 'Berita berhasil dihapus!');
    }

    /* ============================================================
       ✨ Routine-Level Refactoring Methods
       ============================================================ */

    // 1. Ekstraksi validasi
    private function validateBerita(Request $request)
    {
        return $request->validate([
            'judul'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'konten'  => 'required|string',
        ]);
    }

    // 2. Ekstraksi upload foto
    private function uploadFoto(Request $request)
    {
        return $request->hasFile('foto')
            ? $request->file('foto')->store('berita', 'public')
            : null;
    }

    // 3. Ekstraksi hapus foto lama
    private function hapusFotoLama($fotoLama)
    {
        if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
            Storage::disk('public')->delete($fotoLama);
        }
    }
}
