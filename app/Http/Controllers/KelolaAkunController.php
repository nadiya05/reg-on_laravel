<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KelolaAkunController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return view('admin.kelola-akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.kelola-akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'jenis_kelamin' => 'required',
            'no_telp' => 'required',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('password', 'foto');
        $data['password'] = Hash::make($request->password);

        // simpan foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images', 'public');
            $data['foto'] = $path; // simpan path images/nama.jpg ke DB
        }

        User::create($data);

        return redirect()->route('admin.kelola-akun')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.kelola-akun.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik' => 'required|unique:users,nik,' . $id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'jenis_kelamin' => 'required',
            'no_telp' => 'required',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('password', 'foto');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // update foto jika ada upload baru
        if ($request->hasFile('foto')) {
            // hapus foto lama
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('images', 'public');
            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.kelola-akun')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // hapus foto juga
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()->route('admin.kelola-akun')->with('success', 'User berhasil dihapus!');
    }

    public function updateApi(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'nik' => 'required|unique:users,nik,' . $id,
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'jenis_kelamin' => 'required',
        'no_telp' => 'required',
        'password' => 'nullable|min:6',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except('password', 'foto');

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    if ($request->hasFile('foto')) {
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
        $path = $request->file('foto')->store('images', 'public');
        $data['foto'] = $path;
    }

    $user->update($data);

    return response()->json([
        'message' => 'User berhasil diperbarui!',
        'user' => $user,
    ]);
}
}
