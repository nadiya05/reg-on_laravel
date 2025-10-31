<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKtp;
use App\Models\User;
use Barryvdh\DomPDF\Facade\PDF;

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

            return redirect()
                ->route('pengajuan-ktp.status')
                ->with('success', 'Status pengajuan berhasil diperbarui.');
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
