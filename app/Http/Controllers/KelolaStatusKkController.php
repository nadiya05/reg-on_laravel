<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKk;
use App\Models\User;
use App\Models\Notifikasi;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;

class KelolaStatusKkController extends Controller
{
    /**
     * 🔹 Tampilkan daftar pengajuan KK beserta statusnya.
     */
    public function index(Request $request)
    {
        $query = PengajuanKk::select('id', 'nik', 'nama', 'jenis_kk', 'tanggal_pengajuan', 'status', 'keterangan');

        // 🔍 Fitur pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jenis_kk', 'like', "%{$search}%");
            });
        }

        // 🔢 Pagination
        $data = $query->orderBy('tanggal_pengajuan', 'desc')
                      ->paginate(10)
                      ->withQueryString();

        return view('admin.pengajuan-kk.status', compact('data'));
    }

    /**
     * 🔹 Ubah status pengajuan KK dan kirim notifikasi ke user.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,selesai,ditolak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pengajuan = PengajuanKk::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->keterangan = $request->keterangan ?? null;
        $pengajuan->save();

        // 🔔 Siapkan pesan notifikasi
        $pesan = 'Status pengajuan KK Anda telah berubah menjadi: ' . ucfirst($request->status);

        // Tambahkan keterangan jika status ditolak
        if ($request->status === 'ditolak' && $request->filled('keterangan')) {
            $pesan .= "\nAlasan penolakan: " . $request->keterangan;
        }

        // 🔔 Simpan notifikasi
        Notifikasi::create([
            'user_id' => $pengajuan->user_id,
            'judul' => 'Status Pengajuan KK Diperbarui',
            'pesan' => $pesan,
            'tanggal' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 'belum_dibaca',
            'tipe_pengajuan' => 'KK',
            'pengajuan_id' => $pengajuan->id,
        ]);

        return redirect()
            ->route('admin.pengajuan-kk.status')
            ->with('success', 'Status pengajuan berhasil diperbarui dan notifikasi dikirim.');
    }

    /**
     * 🔹 Tampilkan resume pengajuan KK tertentu.
     */
    public function resume($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $user = User::findOrFail($pengajuan->user_id);
        return view('resume_pengajuan', compact('pengajuan', 'user'));
    }

    /**
     * 🔹 Cetak resume pengajuan KK ke PDF.
     */
    public function cetakResumePdf($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $user = $pengajuan->user; // lebih rapi kalau ada relasi

        $pdf = Pdf::loadView('cetak_resume', [
            'pengajuan'     => $pengajuan,
            'user'          => $user,
            'tipePengajuan' => 'KK', 
        ])->setPaper('A4', 'portrait');

        $fileName = 'Resume_KK_' . $pengajuan->nomor_antrean . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * 🔹 Hapus data pengajuan KK.
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $pengajuan->delete();

        return redirect()->back()->with('success', 'Data pengajuan KK berhasil dihapus!');
    }
}
