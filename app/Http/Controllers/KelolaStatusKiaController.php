<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKia;
use App\Models\User;
use App\Models\Notifikasi;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;

class KelolaStatusKiaController extends Controller
{
    /**
     * 🔹 Tampilkan daftar pengajuan KIA beserta statusnya.
     */
    public function index(Request $request)
    {
        $query = PengajuanKia::select('id', 'nik', 'nama', 'jenis_kia', 'tanggal_pengajuan', 'status', 'keterangan');

        // 🔍 fitur pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('jenis_kia', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }

        // 🔢 urut & paginate
        $data = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(10);

        // kirim ke view
        return view('admin.pengajuan-kia.status', compact('data'));
    }

    /**
     * 🔹 Ubah status pengajuan dan kirim notifikasi ke user.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,selesai,ditolak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pengajuan = PengajuanKia::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->keterangan = $request->keterangan ?? null;
        $pengajuan->save();

        // 🔔 Siapkan pesan notifikasi
        $pesan = 'Status pengajuan KIA Anda telah berubah menjadi: ' . ucfirst($request->status);

        // Tambahkan alasan penolakan jika statusnya ditolak
        if ($request->status === 'ditolak' && $request->filled('keterangan')) {
            $pesan .= "\nAlasan penolakan: " . $request->keterangan;
        }

        // Simpan notifikasi ke tabel notifikasi
        Notifikasi::create([
            'user_id' => $pengajuan->user_id,
            'judul' => 'Status Pengajuan KIA Diperbarui',
            'pesan' => $pesan,
            'tanggal' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => 'belum_dibaca',
            'tipe_pengajuan' => 'KIA',
            'pengajuan_id' => $pengajuan->id,
        ]);

        return redirect()
            ->route('admin.pengajuan-kia.status')
            ->with('success', 'Status pengajuan berhasil diperbarui dan notifikasi dikirim.');
    }

    /**
     * 🔹 Tampilkan resume pengajuan KIA tertentu.
     */
    public function resume($id)
    {
        $pengajuan = PengajuanKia::findOrFail($id);
        $user = User::findOrFail($pengajuan->user_id);
        return view('resume_pengajuan', compact('pengajuan', 'user'));
    }

    /**
     * 🔹 Cetak resume pengajuan KIA ke PDF.
     */
    public function cetakResumePdf($id)
    {
        $pengajuan = PengajuanKia::findOrFail($id);
        $user = $pengajuan->user; 

        $pdf = Pdf::loadView('cetak_resume', [
            'pengajuan'     => $pengajuan,
            'user'          => $user,
            'tipePengajuan' => 'KIA', 
        ])->setPaper('A4', 'portrait');

        $fileName = 'Resume_KIA_' . $pengajuan->nomor_antrean . '.pdf';

        return $pdf->download($fileName);
    }
    /**
     * 🔹 Hapus data pengajuan KIA.
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanKia::findOrFail($id);
        $pengajuan->delete();

        return redirect()->back()->with('success', 'Data pengajuan KIA berhasil dihapus!');
    }
}
