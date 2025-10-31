<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelolaBeritaController extends Controller
{
    // 📝 Tampilkan semua data berita
    public function index(Request $request)
    {
        $search = $request->input('search');

        $berita = Berita::when($search, function ($query, $search) {
            return $query->where('judul', 'like', "%{$search}%");
        })
        ->orderBy('tanggal', 'desc')
        ->paginate(5); // bisa ubah ke 10 kalau mau

        return view('admin.kelola-berita.index', compact('berita'));
    }
    // ➕ Form tambah berita
    public function create()
    {
        return view('admin.kelola-berita.create');
    }

    // 💾 Simpan berita baru
    public function store(Request $request)
    {
        $request->validate([
            'judul'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'konten'  => 'required|string',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('berita', 'public'); // simpan ke storage/app/public/berita
        }

        Berita::create([
            'judul'   => $request->judul,
            'tanggal' => $request->tanggal,
            'foto'    => $fotoPath,
            'konten'  => $request->konten,
        ]);

        return redirect()->route('admin.kelola-berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    // ✏️ Form edit berita
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.kelola-berita.edit', compact('berita'));
    }

    // 🔄 Update berita
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'konten'  => 'required|string',
        ]);

        $berita = Berita::findOrFail($id);

        // cek jika ada foto baru diupload
        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }

            $fotoPath = $request->file('foto')->store('berita', 'public');
            $berita->foto = $fotoPath;
        }

        $berita->judul   = $request->judul;
        $berita->tanggal = $request->tanggal;
        $berita->konten  = $request->konten;
        $berita->save();

        return redirect()->route('admin.kelola-berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    // 🗑️ Hapus berita
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // hapus foto dari storage jika ada
        if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
            Storage::disk('public')->delete($berita->foto);
        }

        $berita->delete();

        return redirect()->route('admin.kelola-berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}
