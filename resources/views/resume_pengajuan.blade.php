@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4 text-center">
        Resume Pendaftaran Antrean {{ strtoupper($pengajuan->jenis_pengajuan ?? $pengajuan->jenis_ktp ?? $pengajuan->jenis_kia ?? 'Dokumen') }}
    </h3>

    <div class="card p-4 shadow" style="max-width: 550px; margin: auto; border-radius: 15px;">
        <h5 class="text-center mb-4">
            <strong>Resume Pendaftaran Antrean</strong>
        </h5>

        {{-- 🔹 Data Umum --}}
        <p><strong>Nomor Antrian:</strong> {{ $pengajuan->nomor_antrean }}</p>
        <p><strong>NIK:</strong> {{ $pengajuan->nik }}</p>
        <p><strong>Nama:</strong> {{ $pengajuan->nama }}</p>
        <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
        <p><strong>No. Telepon:</strong> {{ $user->no_telp ?? '-' }}</p>

        {{-- 🔹 Jenis dokumen otomatis menyesuaikan --}}
        @if(isset($pengajuan->jenis_ktp))
            <p><strong>Jenis KTP:</strong> {{ $pengajuan->jenis_ktp }}</p>
        @elseif(isset($pengajuan->jenis_kia))
            <p><strong>Jenis KIA:</strong> {{ $pengajuan->jenis_kia }}</p>
        @elseif(isset($pengajuan->jenis_kk))
            <p><strong>Jenis KK:</strong> {{ $pengajuan->jenis_kk }}</p>
        @endif

        <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->tanggal_pengajuan }}</p>

        {{-- 🔹 Reminder khusus jenis tertentu --}}
        @if(isset($pengajuan->jenis_ktp) && $pengajuan->jenis_ktp == 'Pemula')
            <div class="alert alert-info text-center mt-3" style="font-weight: 600;">
                Datang ke kecamatan 30 menit setelah mencetak nomor antrean
                <br>(untuk pengajuan KTP Pemula)
            </div>
        @endif

        {{-- 🔹 Tombol cetak PDF --}}
        <div class="text-center mt-4">
            @if(isset($pengajuan->jenis_ktp))
                <a href="{{ route('cetak_resume.ktp.pdf', $pengajuan->id) }}" class="btn btn-primary">Cetak</a>
            @elseif(isset($pengajuan->jenis_kia))
                <a href="{{ route('cetak_resume.kia.pdf', $pengajuan->id) }}" class="btn btn-primary">Cetak</a>
            @elseif(isset($pengajuan->jenis_kk))
                <a href="{{ route('cetak_resume.kk.pdf', $pengajuan->id) }}" class="btn btn-primary">Cetak</a>
            @endif
        </div>
    </div>
</div>
@endsection
