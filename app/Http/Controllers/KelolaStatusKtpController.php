<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKtp;
use App\Models\User;
use PDF;

class KelolaStatusKtpController extends Controller
{
    /**
     * Tampilkan daftar pengajuan KTP beserta statusnya.
     */
    public function index()
    {
        $data = PengajuanKtp::select('id', 'nik', 'nama', 'jenis_ktp', 'tanggal_pengajuan', 'status')->get();
        return view('admin.pengajuan-ktp.status', compact('data'));
    }

    /**
     * Ubah status pengajuan KTP langsung dari dropdown.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,selesai,ditolak',
        ]);

        $pengajuan = PengajuanKtp::findOrFail($id);
        $pengajuan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
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
        $user = User::findOrFail($pengajuan->user_id);

        $pdf = PDF::loadView('cetak_resume', compact('pengajuan', 'user'))
                ->setPaper('A4', 'portrait');

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
