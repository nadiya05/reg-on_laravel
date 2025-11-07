<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KelolaNotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::with('user')->latest()->get();
        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    public function destroy($id)
    {
        Notifikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
