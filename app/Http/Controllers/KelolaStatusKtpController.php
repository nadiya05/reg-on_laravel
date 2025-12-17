<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKtp;
use App\Models\User;
use App\Models\Notifikasi;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;

class KelolaStatusKtpController extends Controller
{
    /**
     * Tampilkan daftar pengajuan KTP beserta statusnya.
     */
    public function index(Request $request)
    {
        $query = PengajuanKtp::select('id', 'nik', 'nama', 'jenis_ktp', 'tanggal_pengajuan', 'status', 'keterangan');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('jenis_ktp', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal_pengajuan', 'desc')
                    ->paginate(10)
                    ->appends(['search' => $request->search]);

        return view('admin.pengajuan-ktp.status', compact('data'));
    }

    /**
     * Update status pengajuan dan kirim notifikasi ke user.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:sedang diproses,selesai,ditolak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pengajuan = PengajuanKtp::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->keterangan = $request->keterangan ?? null;
        $pengajuan->save();

        // 🔹 Siapkan pesan notifikasi
        $pesan = 'Status pengajuan KTP Anda telah berubah menjadi: ' . ucfirst($request->status);
        
        // 🔹 Tambahkan keterangan kalau statusnya ditolak
        if ($request->status === 'ditolak' && $request->filled('keterangan')) {
            $pesan .= "\nAlasan penolakan: " . $request->keterangan;
        }
        // 🔹 Simpan notifikasi ke tabel
        Notifikasi::create([
            'user_id' => $pengajuan->user_id,
            'judul' => 'Status Pengajuan KTP Diperbarui',
            'pesan' => $pesan,
            'tanggal' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 'belum_dibaca',
            'tipe_pengajuan' => 'KTP',
            'pengajuan_id' => $pengajuan->id,
        ]);

        return redirect()
            ->route('pengajuan-ktp.status')
            ->with('success', 'Status pengajuan berhasil diperbarui dan notifikasi dikirim.');
    }
    /**
     * Tampilkan resume pengajuan tertentu.
     */
    public function resume($id)
    {
        $pengajuan = PengajuanKtp::findOrFail($id);
        $user = User::findOrFail($pengajuan->user_id);
        return view('resume_pengajuan', compact('pengajuan', 'user'));
    }

    /**
     * Cetak resume ke PDF.
     */
    public function cetakResumePdf($id)
    {
        $pengajuan = PengajuanKtp::findOrFail($id);
        $user = $pengajuan->user; // lebih rapi kalau ada relasi

        $pdf = Pdf::loadView('cetak_resume', [
            'pengajuan'     => $pengajuan,
            'user'          => $user,
            'tipePengajuan' => 'KTP', 
        ])->setPaper('A4', 'portrait');

        $fileName = 'Resume_KTP_' . $pengajuan->nomor_antrean . '.pdf';

        return $pdf->download($fileName);
    }
    /**
     * Hapus data pengajuan KTP.
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanKtp::findOrFail($id);
        $pengajuan->delete();

        return redirect()->back()->with('success', 'Data pengajuan berhasil dihapus!');
    }
}
