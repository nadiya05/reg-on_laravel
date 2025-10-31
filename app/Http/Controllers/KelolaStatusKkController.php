<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKk;
use App\Models\User;
use Barryvdh\DomPDF\Facade\PDF;

class KelolaStatusKkController extends Controller
{
    /**
     * 🔹 Tampilkan daftar pengajuan KK beserta statusnya.
     */
    public function index(Request $request)
    {
        $query = PengajuanKk::select('id', 'nik', 'nama', 'jenis_kk', 'tanggal_pengajuan', 'status');

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
                    ->withQueryString(); // biar pagination tetap bawa query pencarian

        return view('admin.pengajuan-kk.status', compact('data'));
    }
    /**
     * 🔹 Ubah status pengajuan KK langsung dari dropdown.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,selesai,ditolak',
        ]);

        $pengajuan = PengajuanKk::findOrFail($id);
        $pengajuan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pengajuan KK berhasil diperbarui!');
    }

    /**
     * 🔹 Tampilkan resume pengajuan KK tertentu.
     */
    public function resume($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $user = User::findOrFail($pengajuan->user_id);
        return view('admin.pengajuan-kk.resume_pengajuan', compact('pengajuan', 'user'));
    }

    /**
     * 🔹 Cetak resume pengajuan KK ke PDF.
     */
    public function cetakResumePdf($id)
    {
        $pengajuan = PengajuanKk::findOrFail($id);
        $user = User::findOrFail($pengajuan->user_id);

        $pdf = PDF::loadView('admin.pengajuan-kk.cetak_resume_kk', compact('pengajuan', 'user'))
                ->setPaper('A4', 'portrait');

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
