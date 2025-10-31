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
        $tanggal = $request->input('tanggal');

        $queryKtp = PengajuanKtp::query();
        $queryKk = PengajuanKk::query();
        $queryKia = PengajuanKia::query();

        // Filter berdasarkan tanggal pengajuan
        if ($tanggal) {
            $queryKtp->whereDate('tanggal_pengajuan', $tanggal);
            $queryKk->whereDate('tanggal_pengajuan', $tanggal);
            $queryKia->whereDate('tanggal_pengajuan', $tanggal);
        }

        $totalPengajuanKtp = $queryKtp->count();
        $totalPengajuanKk = $queryKk->count();
        $totalPengajuanKia = $queryKia->count();
        $allUsers = User::count();

        // Detail data untuk tabel di bawah grafik
        $dataKtp = $queryKtp->latest()->get();
        $dataKk = $queryKk->latest()->get();
        $dataKia = $queryKia->latest()->get();

        return view('admin.beranda', compact(
            'tanggal',
            'totalPengajuanKtp',
            'totalPengajuanKk',
            'totalPengajuanKia',
            'allUsers',
            'dataKtp',
            'dataKk',
            'dataKia',
        ));
    }

    public function downloadPdf(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $queryKtp = PengajuanKtp::query();
        $queryKk = PengajuanKk::query();
        $queryKia = PengajuanKia::query();

        if ($tanggal) {
            $queryKtp->whereDate('tanggal_pengajuan', $tanggal);
            $queryKk->whereDate('tanggal_pengajuan', $tanggal);
            $queryKia->whereDate('tanggal_pengajuan', $tanggal);
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
            'tanggal',
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