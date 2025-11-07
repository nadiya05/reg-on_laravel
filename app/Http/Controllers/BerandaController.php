<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PengajuanKtp;
use App\Models\PengajuanKk;
use App\Models\PengajuanKia;
use Illuminate\Http\Request;
use PDF;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $queryKtp = PengajuanKtp::query();
        $queryKk = PengajuanKk::query();
        $queryKia = PengajuanKia::query();

        // 🔹 Filter berdasarkan rentang tanggal
        if ($tanggalAwal && $tanggalAkhir) {
            $queryKtp->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
            $queryKk->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
            $queryKia->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
        }

        $totalPengajuanKtp = $queryKtp->count();
        $totalPengajuanKk = $queryKk->count();
        $totalPengajuanKia = $queryKia->count();
        $allUsers = User::count();

        $dataKtp = $queryKtp->latest()->get();
        $dataKk = $queryKk->latest()->get();
        $dataKia = $queryKia->latest()->get();

        return view('admin.beranda', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPengajuanKtp',
            'totalPengajuanKk',
            'totalPengajuanKia',
            'allUsers',
            'dataKtp',
            'dataKk',
            'dataKia'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $queryKtp = PengajuanKtp::query();
        $queryKk = PengajuanKk::query();
        $queryKia = PengajuanKia::query();

        if ($tanggalAwal && $tanggalAkhir) {
            $queryKtp->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
            $queryKk->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
            $queryKia->whereBetween('tanggal_pengajuan', [$tanggalAwal, $tanggalAkhir]);
        }

        $totalPengajuanKtp = $queryKtp->count();
        $totalPengajuanKk = $queryKk->count();
        $totalPengajuanKia = $queryKia->count();
        $allUsers = User::count();

        $dataKtp = $queryKtp->get();
        $dataKk = $queryKk->get();
        $dataKia = $queryKia->get();

        $totalPengajuanDokumen = $totalPengajuanKtp + $totalPengajuanKk + $totalPengajuanKia;

        $pdf = PDF::loadView('admin.laporan-pengajuan', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPengajuanKtp',
            'totalPengajuanKk',
            'totalPengajuanKia',
            'allUsers',
            'dataKtp',
            'dataKk',
            'dataKia',
            'totalPengajuanDokumen'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-pengajuan.pdf');
    }
}
