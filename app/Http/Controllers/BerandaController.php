<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        // Data dummy untuk ditampilkan, bisa diganti dengan query database
        $data = [
            'totalPenduduk' => 10,
            'totalKTP' => 5,
            'totalKK' => 20,
            'totalKIA' => 12,
        ];

        return view('admin.beranda', compact('data'));
    }
}
