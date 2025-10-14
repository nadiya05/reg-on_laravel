<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKia;
use App\Models\User;
use PDF;

class KelolaStatusKiaController extends Controller
{
    /**
     * 🔹 Tampilkan daftar pengajuan KIA beserta statusnya.
     */
    public function index()
    {
        $data = PengajuanKia::select('id', 'nik', 'nama', 'jenis_kia', 'tanggal_pengajuan', 'status')->get();
        return view('admin.pengajuan-kia.status', compact('data'));
    }

    /**
     * 🔹 Ubah status pengajuan KIA langsung dari dropdown.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,selesai,ditolak',
        ]);

        $pengajuan = PengajuanKia::findOrFail($id);
        $pengajuan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pengajuan KIA berhasil diperbarui!');
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
        $user = User::findOrFail($pengajuan->user_id);

        $pdf = PDF::loadView('cetak_resume_kia', compact('pengajuan', 'user'))
                ->setPaper('A4', 'portrait');

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
