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
        $notifikasiSearch = $request->input('search'); // refactoring data-level 2 ubah variabel $search menjadi $notifikasiSearch

        $query = Notifikasi::with('user')
            ->when($notifikasiSearch, function ($q) use ($notifikasiSearch) {
                $q->where(function ($sub) use ($notifikasiSearch) {
                    $sub->where('judul', 'like', "%{$notifikasiSearch}%")
                        ->orWhere('pesan', 'like', "%{$notifikasiSearch}%")
                        ->orWhere('tipe_pengajuan', 'like', "%{$notifikasiSearch}%")
                        ->orWhereHas('user', fn($u) =>
                            $u->where('name', 'like', "%{$notifikasiSearch}%")
                        );
                });
            })
            ->orderBy('tanggal', 'desc');
        $notifikasi = $query->paginate(10)->appends(['search' => $notifikasiSearch]);
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

        return view('admin.notifikasi.index', compact('notifikasi', 'notifikasiSearch'));
    }

    public function destroy($id)
    {
        Notifikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
