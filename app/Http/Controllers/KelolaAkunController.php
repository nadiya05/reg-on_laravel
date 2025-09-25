<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
            'no_telp' => 'required'
        ]);

        User::create($request->all());
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
            'no_telp' => 'required'
        ]);

        $user->update($request->all());
        return redirect()->route('admin.kelola-akun')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.kelola-akun')->with('success', 'User berhasil dihapus!');
    }
}

