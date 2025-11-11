<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\PengajuanKtp;
use App\Models\PengajuanKk;
use App\Models\PengajuanKia;
use Illuminate\Http\Request;

class KelolaNotifikasiController extends Controller
{
    public function index(Request $request)
    {
        // 🔍 Fitur pencarian
        $search = $request->input('search');

        $query = Notifikasi::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%")
                  ->orWhere('tipe_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            })
            ->orderBy('tanggal', 'desc');

        // 🔢 Pagination
        $notifikasi = $query->paginate(10)->appends(['search' => $search]);

        // 🔗 Tambahkan data pengajuan (nama + jenis dokumen)
        $notifikasi->getCollection()->transform(function ($n) {
            $namaPengajuan = '-';
            $jenisDokumen = '-';
            $tipe = strtoupper($n->tipe_pengajuan ?? '-');

            switch ($tipe) {
                case 'KTP':
                    $data = PengajuanKtp::find($n->pengajuan_id);
                    if ($data) {
                        $namaPengajuan = $data->nama;
                        $jenisDokumen = $data->jenis_ktp;
                    }
                    break;

                case 'KK':
                    $data = PengajuanKk::find($n->pengajuan_id);
                    if ($data) {
                        $namaPengajuan = $data->nama;
                        $jenisDokumen = $data->jenis_kk;
                    }
                    break;

                case 'KIA':
                    $data = PengajuanKia::find($n->pengajuan_id);
                    if ($data) {
                        $namaPengajuan = $data->nama;
                        $jenisDokumen = $data->jenis_kia;
                    }
                    break;
            }

            $n->nama_pengajuan = $namaPengajuan;
            $n->jenis_dokumen = ucfirst($jenisDokumen);
            $n->tipe_pengajuan_label = ucfirst(strtolower($tipe));

            return $n;
        });

        return view('admin.notifikasi.index', compact('notifikasi', 'search'));
    }

    public function destroy($id)
    {
        Notifikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
